<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Cluster;
use App\Models\File;
use App\Models\Folder;
use App\Models\FolderType;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $year = $request->input('year', 'all');
        $statusDokumen = $request->input('statusDokumen', ['all']);
        $clustersInput = $request->input('clusters', ['all']);
        $categoriesInput = $request->input('categories', ['all']);
        $statusProjectInput = $request->input('statusProject', ['all']);

        $project = Folder::query();
        $files = File::query();

        // Get the collection of created_at values
        $years = (clone $project)->distinct('year')->orderBy('year')->pluck('year')->toArray();
        $statusDokumens = (clone $project)->distinct('status')->orderBy('status')->pluck('status')->toArray();
        $clustersList = Cluster::all();
        $categoryList = Category::all();
        
        $getStatusProjects = (clone $project)->distinct('is_trackable')->orderBy('is_trackable')->pluck('is_trackable')->toArray();
        $statusProjects = array_map(function ($status) {
            return (object) [
                'id' => $status,
                'name' => $status ? 'Trackable' : 'Non-Trackable'
            ];
        }, $getStatusProjects);    

        // Group the dates by year
        // $years = $createdDates->groupBy(function ($date) {
        //     return Carbon::parse($date)->format('Y');
        // })->keys()->toArray();

        $countUsers = User::count();
        $countApproved = User::where('status', User::APPROVED)->count();
        $countRejected = User::where('status', User::REJECT)->count();
        $countPending = User::where('status', User::PENDING)->count();
        $countDeleted = User::where('status', User::DELETE)->count();

        if (auth()->user()->hasRole(['superadmin', 'admin'])) {

            $countProjects = (clone $project)
                ->when($year != "all", fn ($query) => $query->where('year', $year))
                ->when(!in_array('all', $statusDokumen), fn ($query) => $query->whereIn('status', $statusDokumen))
                ->when(!in_array('all', $clustersInput), fn ($query) => $query->whereIn('cluster_id', $clustersInput))
                ->when(!in_array('all', $statusProjectInput), fn ($query) => $query->whereIn('is_trackable', $statusProjectInput))
                ->when(!in_array('all', $categoriesInput), function ($query) use ($categoriesInput) {
                    $query->whereHas('files', function ($subQuery) use ($categoriesInput) {
                        $subQuery->whereHas('categories', function ($innerSubQuery) use ($categoriesInput) {
                            $innerSubQuery->whereIn('categories.id', $categoriesInput);
                        });
                    });
                })
                ->count();

            $isTrackableSelected = in_array('1', $statusProjectInput);
            $isNonTrackableSelected = in_array('0', $statusProjectInput);
            
            // The base query without trackable conditions
            $getCountTrackable = (clone $project)
                ->when($year != "all", fn ($query) => $query->where('year', $year))
                ->when(!in_array('all', $statusDokumen), fn ($query) => $query->whereIn('status', $statusDokumen))
                ->when(!in_array('all', $clustersInput), fn ($query) => $query->whereIn('cluster_id', $clustersInput))
                ->when(!in_array('all', $categoriesInput), function ($query) use ($categoriesInput) {
                    $query->whereHas('files', function ($subQuery) use ($categoriesInput) {
                        $subQuery->whereHas('categories', function ($innerSubQuery) use ($categoriesInput) {
                            $innerSubQuery->whereIn('categories.id', $categoriesInput);
                        });
                    });
                });
            
            if ($isTrackableSelected && !$isNonTrackableSelected) {
                // Only count trackable
                $countTrackable = (clone $getCountTrackable)->where('is_trackable', true)->count();
                $countNonTrackable = 0;
            } elseif (!$isTrackableSelected && $isNonTrackableSelected) {
                // Only count non-trackable
                $countNonTrackable = (clone $getCountTrackable)->where('is_trackable', false)->count();
                $countTrackable = 0;
            } else {
                // Count both separately
                $countTrackable = (clone $getCountTrackable)->where('is_trackable', true)->count();
                $countNonTrackable = (clone $getCountTrackable)->where('is_trackable', false)->count();
            }             

            // Project tertunggak
            $projectOnTime = (clone $project)
                ->when($year != "all", fn ($query) => $query->where('year', $year))
                ->when(!in_array('all', $statusDokumen), fn ($query) => $query->whereIn('status', $statusDokumen))
                ->when(!in_array('all', $clustersInput), fn ($query) => $query->whereIn('cluster_id', $clustersInput))
                ->when(!in_array('all', $statusProjectInput), fn ($query) => $query->whereIn('is_trackable', $statusProjectInput))
                ->when(!in_array('all', $categoriesInput), function ($query) use ($categoriesInput) {
                    $query->whereHas('files', function ($subQuery) use ($categoriesInput) {
                        $subQuery->whereHas('categories', function ($innerSubQuery) use ($categoriesInput) {
                            $innerSubQuery->whereIn('categories.id', $categoriesInput);
                        });
                    });
                })
                ->where('status_date', Folder::ONTIME)
                
                ->count();
            $projectOverdue = (clone $project)
                ->when($year != "all", fn ($query) => $query->where('year', $year))
                ->when(!in_array('all', $statusDokumen), fn ($query) => $query->whereIn('status', $statusDokumen))
                ->when(!in_array('all', $clustersInput), fn ($query) => $query->whereIn('cluster_id', $clustersInput))
                ->when(!in_array('all', $statusProjectInput), fn ($query) => $query->whereIn('is_trackable', $statusProjectInput))
                ->when(!in_array('all', $categoriesInput), function ($query) use ($categoriesInput) {
                    $query->whereHas('files', function ($subQuery) use ($categoriesInput) {
                        $subQuery->whereHas('categories', function ($innerSubQuery) use ($categoriesInput) {
                            $innerSubQuery->whereIn('categories.id', $categoriesInput);
                        });
                    });
                })
                ->where('status_date', Folder::OVERDUE)
                
                ->count();

            // Project with status lengkap vs tak lengkap
            $projectLengkap = (clone $project)
                ->when($year != "all", fn ($query) => $query->where('year', $year))
                ->when(!in_array('all', $statusDokumen), fn ($query) => $query->whereIn('status', $statusDokumen))
                ->when(!in_array('all', $clustersInput), fn ($query) => $query->whereIn('cluster_id', $clustersInput))
                ->when(!in_array('all', $statusProjectInput), fn ($query) => $query->whereIn('is_trackable', $statusProjectInput))
                ->when(!in_array('all', $categoriesInput), function ($query) use ($categoriesInput) {
                    $query->whereHas('files', function ($subQuery) use ($categoriesInput) {
                        $subQuery->whereHas('categories', function ($innerSubQuery) use ($categoriesInput) {
                            $innerSubQuery->whereIn('categories.id', $categoriesInput);
                        });
                    });
                })
                ->where('status', Folder::COMPLETE)
                
                ->count();
            $projectBelumLengkap = (clone $project)
                ->when($year != "all", fn ($query) => $query->where('year', $year))
                ->when(!in_array('all', $statusDokumen), fn ($query) => $query->whereIn('status', $statusDokumen))
                ->when(!in_array('all', $clustersInput), fn ($query) => $query->whereIn('cluster_id', $clustersInput))
                ->when(!in_array('all', $statusProjectInput), fn ($query) => $query->whereIn('is_trackable', $statusProjectInput))
                ->when(!in_array('all', $categoriesInput), function ($query) use ($categoriesInput) {
                    $query->whereHas('files', function ($subQuery) use ($categoriesInput) {
                        $subQuery->whereHas('categories', function ($innerSubQuery) use ($categoriesInput) {
                            $innerSubQuery->whereIn('categories.id', $categoriesInput);
                        });
                    });
                })
                ->where('status', Folder::INCOMPLETE)
                
                ->count();

            // Projects with categories data
            $categories = Category::withCount(['files as project_file_count' => function ($query) use ($year, $statusDokumen, $clustersInput, $categoriesInput, $statusProjectInput) {
                $query->when(!in_array('all', $categoriesInput), function ($query) use ($categoriesInput) {
                    $query->whereIn('category_id', $categoriesInput);
                })
                ->whereHas('folder', function ($query) use ($year, $statusDokumen, $clustersInput, $statusProjectInput) {
                    $query->when($year != "all", fn ($query) => $query->where('year', $year))
                        ->when(!in_array('all', $statusDokumen), fn ($query) => $query->whereIn('status', $statusDokumen))
                        ->when(!in_array('all', $clustersInput), fn ($query) => $query->whereIn('cluster_id', $clustersInput))
                        ->when(!in_array('all', $statusProjectInput), fn ($query) => $query->whereIn('is_trackable', $statusProjectInput));
                });
            }])
            ->get();

            // Project with clusters data
            $clusters = Cluster::withCount(['folders as project_count' => function ($query) use ($year, $statusDokumen, $clustersInput, $categoriesInput, $statusProjectInput) {
                $query->when(!in_array('all', $categoriesInput), function ($query) use ($categoriesInput) {
                    $query->whereHas('files', function ($subQuery) use ($categoriesInput) {
                        $subQuery->whereHas('categories', function ($innerSubQuery) use ($categoriesInput) {
                            $innerSubQuery->whereIn('categories.id', $categoriesInput);
                        });
                    });
                })
                ->when($year != "all", fn ($query) => $query->where('folders.year', $year))
                    ->when(!in_array('all', $statusDokumen), fn ($query) => $query->whereIn('folders.status', $statusDokumen))
                    ->when(!in_array('all', $clustersInput), fn ($query) => $query->whereIn('cluster_id', $clustersInput))
                    ->when(!in_array('all', $statusProjectInput), fn ($query) => $query->whereIn('is_trackable', $statusProjectInput));
            }])
            ->get();

            // Project with type data
            $types = FolderType::withCount(['folders as project_count' => function ($query) use ($year, $statusDokumen, $clustersInput, $categoriesInput, $statusProjectInput) {
                $query->when(!in_array('all', $categoriesInput), function ($query) use ($categoriesInput) {
                    $query->whereHas('files', function ($subQuery) use ($categoriesInput) {
                        $subQuery->whereHas('categories', function ($innerSubQuery) use ($categoriesInput) {
                            $innerSubQuery->whereIn('categories.id', $categoriesInput);
                        });
                    });
                })
                ->when($year != "all", fn ($query) => $query->where('folders.year', $year))
                    ->when(!in_array('all', $statusDokumen), fn ($query) => $query->whereIn('folders.status', $statusDokumen))
                    ->when(!in_array('all', $clustersInput), fn ($query) => $query->whereIn('cluster_id', $clustersInput))
                    ->when(!in_array('all', $statusProjectInput), fn ($query) => $query->whereIn('is_trackable', $statusProjectInput));
            }])
            ->get();
        } else if (auth()->user()->hasRole('member')) {

            $countProjects = (clone $project)
                ->whereHas('users', fn ($query) => $query->where('users.id', auth()->id()))
                ->when($year != "all", fn ($query) => $query->where('year', $year))
                ->when(!in_array('all', $statusDokumen), fn ($query) => $query->whereIn('status', $statusDokumen))
                ->when(!in_array('all', $clustersInput), fn ($query) => $query->whereIn('cluster_id', $clustersInput))
                ->when(!in_array('all', $statusProjectInput), fn ($query) => $query->whereIn('is_trackable', $statusProjectInput))
                ->when(!in_array('all', $categoriesInput), function ($query) use ($categoriesInput) {
                    $query->whereHas('files', function ($subQuery) use ($categoriesInput) {
                        $subQuery->whereHas('categories', function ($innerSubQuery) use ($categoriesInput) {
                            $innerSubQuery->whereIn('categories.id', $categoriesInput);
                        });
                    });
                })
                ->count();

            $isTrackableSelected = in_array('1', $statusProjectInput);
            $isNonTrackableSelected = in_array('0', $statusProjectInput);
                
            // The base query without trackable conditions
            $getCountTrackable = (clone $project)
                ->whereHas('users', fn ($query) => $query->where('users.id', auth()->id()))
                ->when($year != "all", fn ($query) => $query->where('year', $year))
                ->when(!in_array('all', $statusDokumen), fn ($query) => $query->whereIn('status', $statusDokumen))
                ->when(!in_array('all', $clustersInput), fn ($query) => $query->whereIn('cluster_id', $clustersInput))
                ->when(!in_array('all', $categoriesInput), function ($query) use ($categoriesInput) {
                    $query->whereHas('files', function ($subQuery) use ($categoriesInput) {
                        $subQuery->whereHas('categories', function ($innerSubQuery) use ($categoriesInput) {
                            $innerSubQuery->whereIn('categories.id', $categoriesInput);
                        });
                    });
                });
            
            if ($isTrackableSelected && !$isNonTrackableSelected) {
                // Only count trackable
                $countTrackable = (clone $getCountTrackable)->where('is_trackable', true)->count();
                $countNonTrackable = 0;
            } elseif (!$isTrackableSelected && $isNonTrackableSelected) {
                // Only count non-trackable
                $countNonTrackable = (clone $getCountTrackable)->where('is_trackable', false)->count();
                $countTrackable = 0;
            } else {
                // Count both separately
                $countTrackable = (clone $getCountTrackable)->where('is_trackable', true)->count();
                $countNonTrackable = (clone $getCountTrackable)->where('is_trackable', false)->count();
            }                

            // Base query
            $baseQuery = (clone $project)
            ->when($year != "all", fn ($query) => $query->where('year', $year))
            ->when(!in_array('all', $statusDokumen), fn ($query) => $query->whereIn('status', $statusDokumen))
            ->when(!in_array('all', $clustersInput), fn ($query) => $query->whereIn('cluster_id', $clustersInput))
            ->when(!in_array('all', $statusProjectInput), fn ($query) => $query->whereIn('is_trackable', $statusProjectInput))
            ->when(!in_array('all', $categoriesInput), function ($query) use ($categoriesInput) {
                $query->whereHas('files', function ($subQuery) use ($categoriesInput) {
                    $subQuery->whereHas('categories', function ($innerSubQuery) use ($categoriesInput) {
                        $innerSubQuery->whereIn('categories.id', $categoriesInput);
                    });
                });
            })
            ->whereHas('users', fn ($query) => $query->where('users.id', auth()->id()));

            // Project tertunggak
            $projectOnTime = (clone $baseQuery)
            ->where('status_date', Folder::ONTIME)
            ->count();

            $projectOverdue = (clone $baseQuery)
            ->where('status_date', Folder::OVERDUE)
            ->count();

            // Project with status lengkap vs tak lengkap
            $projectLengkap = (clone $baseQuery)
            ->where('status', Folder::COMPLETE)
            ->count();

            $projectBelumLengkap = (clone $baseQuery)
            ->where('status', Folder::INCOMPLETE)
            ->count();

            $baseQuery = function ($query) use ($year, $statusDokumen, $clustersInput, $statusProjectInput, $categoriesInput) {
                return $query
                    ->when($year != "all", fn ($query) => $query->where('folders.year', $year))
                    ->when(!in_array('all', $statusDokumen), fn ($query) => $query->whereIn('folders.status', $statusDokumen))
                    ->when(!in_array('all', $clustersInput), fn ($query) => $query->whereIn('cluster_id', $clustersInput))
                    ->when(!in_array('all', $statusProjectInput), fn ($query) => $query->whereIn('is_trackable', $statusProjectInput))
                    ->whereHas('users', fn ($query) => $query->where('users.id', auth()->id()));
            };
            
            $categories = Category::withCount(['files as project_file_count' => function ($query) use ($baseQuery, $categoriesInput) {
                $query->when(!in_array('all', $categoriesInput), function ($query) use ($categoriesInput) {
                    $query->whereIn('category_id', $categoriesInput);
                })
                ->whereHas('folder', $baseQuery);
            }])
            ->get();
            
            $clusters = Cluster::withCount(['folders as project_count' => function ($query) use ($baseQuery, $categoriesInput) {
                $query->when(!in_array('all', $categoriesInput), function ($query) use ($categoriesInput) {
                    $query->whereHas('files', function ($subQuery) use ($categoriesInput) {
                        $subQuery->whereHas('categories', function ($innerSubQuery) use ($categoriesInput) {
                            $innerSubQuery->whereIn('categories.id', $categoriesInput);
                        });
                    });
                });
                $baseQuery($query);
            }])
            ->get();
            
            $types = FolderType::withCount(['folders as project_count' => function ($query) use ($baseQuery, $categoriesInput) {
                $query->when(!in_array('all', $categoriesInput), function ($query) use ($categoriesInput) {
                    $query->whereHas('files', function ($subQuery) use ($categoriesInput) {
                        $subQuery->whereHas('categories', function ($innerSubQuery) use ($categoriesInput) {
                            $innerSubQuery->whereIn('categories.id', $categoriesInput);
                        });
                    });
                });
                $baseQuery($query);
            }])
            ->get();            
        }

        // Project tertunggak
        $dataTempohMuatNaik = [
            $projectOnTime, $projectOverdue
        ];
        $labelTempohMuatNaik = [
            Folder::ONTIME, Folder::OVERDUE
        ];

        // Project with status lengkap vs tak lengkap
        $dataStatus = [
            $projectLengkap, $projectBelumLengkap
        ];
        $labelStatus = [
            Folder::COMPLETE, Folder::INCOMPLETE
        ];

        // Projects with categories data
        $categoryCounts = [];
        $categoryLabels = [];

        foreach ($categories as $category) {
            $categoryCounts[] = $category->project_file_count;
            $categoryLabels[] = $category->name;
        }

        // Project with clusters data
        $clusterCounts = [];
        $clusterLabels = [];

        foreach ($clusters as $cluster) {
            $clusterCounts[] = $cluster->project_count;
            $clusterLabels[] = $cluster->name;
        }

        // Project with type data
        $typeCounts = [];
        $typeLabels = [];

        foreach ($types as $type) {
            $typeCounts[] = $type->project_count;
            $typeLabels[] = $type->name;
        }

        return view('dashboard', compact(
            'countProjects',
            'countTrackable',
            'countNonTrackable',
            'countUsers',
            'countApproved',
            'countRejected',
            'countPending',
            'countDeleted',
            'dataTempohMuatNaik',
            'labelTempohMuatNaik',
            'dataStatus',
            'labelStatus',
            'categoryCounts',
            'categoryLabels',
            'clusterCounts',
            'clusterLabels',
            'typeCounts',
            'typeLabels',
            'years',
            'statusDokumens',
            'clustersList',
            'categoryList',
            'statusProjects'
        ));
    }
}
