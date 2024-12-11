# Document Management System

This is a **Document Management System (DMS)** built with **Laravel** and **Livewire**. It allows users to securely upload, organize, and manage documents online with real-time interaction features. 

## Features

- **User Authentication**: Secure user authentication with registration, login, and password management using Laravel's built-in authentication system.
- **Document Upload & Management**: Upload multiple types of documents (PDF, DOCX, PNG, JPG, etc.), organize them by categories and tags, and keep them safe.
- **Real-Time Interaction**: Livewire components for dynamic, real-time updates without the need for page reloads.
- **Search & Filter**: Easy search by document name, type, category, and tags. Filter documents based on various attributes.
- **Admin Panel**: Admin users can manage documents, users, categories, and tags via the backend.
- **Responsive UI**: The system is fully responsive, providing a smooth experience on both desktop and mobile devices.

## Technologies Used

- **Backend**: Laravel 10.x
- **Frontend**: Livewire for dynamic components, Tailwind CSS for styling
- **Database**: MySQL or SQLite
- **Authentication**: Laravel Breeze for simple authentication
- **File Storage**: Local file system or cloud-based storage (e.g., Amazon S3, Google Cloud Storage)

## Installation

### Prerequisites

Before installing the application, ensure you have the following tools installed on your machine:

- PHP >= 8.1
- Composer
- Node.js
- MySQL (or another database such as SQLite)
- Git

### Steps to Set Up

1. Clone the repository:
   ```bash
   git clone https://github.com/mhafiziskandar/document-management.git
   cd document-management
   ```

2. Install PHP dependencies:
   ```bash
   composer install
   ```

3. Configure Environment:
   ```bash
   cp .env.example .env
   ```
   Update the .env file with your database credentials and other environment-specific variables.

4. Generate the application key:
   ```bash
   php artisan key:generate
   ```

5. Set up the database:
   ```bash
   php artisan migrate
   ```

6. Install frontend dependencies:
   ```bash
   npm install
   npm run dev
   ```

7. Seed the database (Optional):
   ```bash
   php artisan db:seed
   ```

8. Start the application:
   ```bash
   php artisan serve
   ```
   Your application will be accessible at http://127.0.0.1:8000

## Usage

### Login/Registration
New users can register and existing users can log in using the authentication system. The system supports password resets as well.

### Document Upload
After logging in, users can upload documents via the file upload interface. These documents are stored and categorized based on the selected tags or categories.

### Search and Filter
Users can search for documents by name, tags, categories, and other attributes. Filters allow narrowing down results.

### Admin Panel
Admin users can manage all aspects of the document management system, including reviewing documents, managing user accounts, and creating or editing categories/tags.

## Directory Structure

- `app/`: Contains the core business logic
  - `Http/Controllers/`: Controllers for handling HTTP requests
  - `Models/`: Eloquent models for database interaction
  - `Livewire/`: Livewire components for dynamic features
- `resources/views/`: Contains Blade views for the UI
  - `livewire/`: Livewire Blade views
- `public/`: Public assets (images, stylesheets, JavaScript)
- `routes/`: Application route definitions
- `database/`: Migrations, seeders, and factories

## Contributing

We welcome contributions to this project. To contribute:

1. Fork the repository
2. Create a new branch for your feature/fix
3. Write tests to cover your changes
4. Ensure all tests pass
5. Submit a pull request for review

Please follow the PSR-12 coding standards when contributing.

## License

This project is open-source and available under the MIT License.

## Contact

- **Author**: Hafiz Iskandar
- **Email**: mhafiziskandar.dev@gmail.com
- **GitHub**: @mhafiziskandar
- **LinkedIn**: Hafiz Iskandar

## Acknowledgments

- Laravel: The backend framework powering the application
- Livewire: Library used for building interactive UI components
- Tailwind CSS: Utility-first CSS framework used for styling
- MySQL: Relational database management system used for data storage