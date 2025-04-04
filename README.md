# Laravel Real-Time Chat Application

A real-time chat application built with Laravel, featuring user authentication, real-time messaging, and unread message counts.

## Features

-   User Authentication
-   Real-time Messaging using Laravel Echo and WebSockets
-   Unread Message Counts
-   Modern UI with Tailwind CSS
-   Responsive Design
-   Message History
-   User List with Online Status

## Prerequisites

-   PHP >= 8.1
-   Composer
-   Node.js & NPM
-   MySQL/PostgreSQL
-   Redis (for broadcasting)

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

    Configure your `.env` file with your database and broadcasting settings:

    ```
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=your_database
    DB_USERNAME=your_username
    DB_PASSWORD=your_password

    BROADCAST_DRIVER=redis
    REDIS_HOST=127.0.0.1
    REDIS_PASSWORD=null
    REDIS_PORT=6379
    ```

5. **Database Setup**

    ```bash
    php artisan migrate
    php artisan db:seed
    ```

6. **Compile Assets**

    ```bash
    npm run dev
    ```

7. **Start the Development Server**
    ```bash
    php artisan serve
    ```

## Project Structure

-   `app/Events/` - Contains event classes for real-time messaging
-   `app/Http/Controllers/` - Contains controllers for handling chat logic
-   `app/Models/` - Contains User and Message models
-   `resources/views/` - Contains Blade templates for the chat interface
-   `routes/` - Contains web routes and broadcasting channels
-   `database/migrations/` - Contains database migrations

## Key Components

1. **Authentication**

    - Laravel's built-in authentication system
    - User registration and login
    - Session management

2. **Real-time Messaging**

    - Laravel Echo for WebSocket connections
    - Redis for broadcasting
    - Event-driven architecture

3. **Frontend**

    - Tailwind CSS for styling
    - JavaScript for real-time updates
    - Responsive design for mobile devices

4. **Database**
    - Users table for user management
    - Messages table for chat history
    - Relationships between users and messages

## Usage

1. Register a new account or login
2. View the list of available users
3. Click on a user to start a conversation
4. Type your message and press Enter or click Send
5. Messages will appear in real-time
6. Unread message counts will update automatically

## Development

To start development:

1. Start the Laravel development server:

    ```bash
    php artisan serve
    ```

2. Start the Vite development server:

    ```bash
    npm run dev
    ```

3. Start Redis server (if using Redis for broadcasting)

## Testing

Run tests using:

```bash
php artisan test
```

## Contributing

1. Fork the repository
2. Create your feature branch
3. Commit your changes
4. Push to the branch
5. Create a new Pull Request

## License

This project is licensed under the MIT License.
