# Laravel Real-Time Chat Application with Blog System

A real-time chat application built with Laravel, featuring user authentication, real-time messaging, per-user unread message counts, and a comprehensive blog system with rich text editing.

## Features

-   User Authentication
-   Real-time Messaging using Laravel Echo and Pusher
-   Per-User Unread Message Counts
-   Blog System with Rich Text Editor
-   Image Upload in Blog Posts
-   Category Management
-   Modern UI with Tailwind CSS
-   Responsive Design
-   Message History
-   User List with Online Status

## Prerequisites

-   PHP >= 8.1
-   Composer
-   Node.js & NPM
-   MySQL/PostgreSQL
-   Pusher account (for broadcasting)

## Installation Steps

1. **Clone the repository**

    ```bash
    git clone <repository-url>
    cd Laravel-app
    ```

2. **Install PHP dependencies**

    ```bash
    composer install
    ```

3. **Install NPM dependencies**

    ```bash
    npm install
    ```

4. **Environment Setup**

    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

    Configure your `.env` file with your database and Pusher settings:

    ```
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=your_database
    DB_USERNAME=your_username
    DB_PASSWORD=your_password

    BROADCAST_DRIVER=pusher
    PUSHER_APP_ID=your_pusher_app_id
    PUSHER_APP_KEY=your_pusher_app_key
    PUSHER_APP_SECRET=your_pusher_app_secret
    PUSHER_HOST=
    PUSHER_PORT=443
    PUSHER_SCHEME=https
    PUSHER_APP_CLUSTER=your_pusher_cluster
    ```

5. **Database Setup**

    ```bash
    php artisan migrate
    php artisan db:seed
    ```

6. **Create Storage Link**

    ```bash
    php artisan storage:link
    ```

7. **Compile Assets**

    ```bash
    npm run dev
    ```

8. **Start the Development Server**
    ```bash
    php artisan serve
    ```

## Project Structure

-   `app/Events/` - Contains event classes for real-time messaging
-   `app/Http/Controllers/` - Contains controllers for handling chat and blog logic
-   `app/Models/` - Contains User, Message, Post, and Category models
-   `app/Policies/` - Contains authorization policies for blog posts
-   `resources/views/` - Contains Blade templates for the chat interface and blog
-   `routes/` - Contains web routes and broadcasting channels
-   `database/migrations/` - Contains database migrations
-   `public/js/` - Contains JavaScript files including the CKEditor upload adapter

## Key Components

1. **Authentication**
    - User registration and login
    - Role-based access control

2. **Real-time Chat**
    - Instant messaging between users
    - Unread message counts
    - Message history

3. **Blog System**
    - Create, read, update, and delete blog posts
    - Rich text editing with CKEditor 5
    - Image upload in blog posts
    - Category management
    - Featured images for posts
    - Meta information for SEO

4. **CKEditor Integration**
    - Rich text editing
    - Custom image upload adapter
    - Image resizing and positioning
    - Tables, lists, and formatting options

## Blog Features

### Post Management
- Create, edit, and delete blog posts
- Set post status (draft, published, archived)
- Set post visibility (public, private)
- Schedule posts with publish date
- Add featured images

### Category Management
- Create, edit, and delete categories
- Organize posts by categories
- Hierarchical categories (parent-child relationships)

### Rich Text Editor
- CKEditor 5 integration
- Image upload and management
- Text formatting (bold, italic, headings)
- Lists and tables
- Links and blockquotes
- Source code editing

### Image Upload
- Upload images directly in the editor
- Custom upload adapter for server-side processing
- Automatic image storage in public/storage/uploads
- Image resizing and positioning options

## Usage

### Creating a Blog Post
1. Navigate to the Posts section
2. Click "Create Post"
3. Fill in the post details (title, body, category, etc.)
4. Use the rich text editor to format your content
5. Upload images using the image upload button
6. Set the post status and visibility
7. Click "Create" to publish

### Managing Categories
1. Navigate to the Categories section
2. Click "Create Category"
3. Fill in the category details
4. Click "Create" to save

## License

This project is licensed under the MIT License - see the LICENSE file for details.
