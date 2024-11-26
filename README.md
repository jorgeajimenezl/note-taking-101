# Task Management Website

This is a task management web application built with Laravel. It allows users to create, manage, and track tasks with various features such as tagging, contributors, and task completion status.

## Features

- User authentication and profile management
- Create, update, and delete tasks
- Tag tasks for better organization
- Assign contributors to tasks with different roles (editor, viewer)
- Mark tasks as completed or uncompleted
- Responsive design with animations for task movements

## Installation

1. Clone the repository:
    ```sh
    git clone https://github.com/jorgeajimenezl/hs-laravel-01.git
    cd hs-laravel-01
    ```

2. Install dependencies:
    ```sh
    composer install
    npm install
    ```

3. Copy the example environment file and configure the environment variables:
    ```sh
    cp .env.example .env
    ```

4. Generate an application key:
    ```sh
    php artisan key:generate
    ```

5. Run the database migrations and seed the database:
    ```sh
    php artisan migrate --seed
    ```

6. Build the front-end assets:
    ```sh
    npm run build
    ```

7. Start the development server:
    ```sh
    php artisan serve
    ```

## Usage

1. Register a new user or log in with an existing account.
2. Navigate to the dashboard to view your tasks.
3. Create new tasks by clicking the "Create" button.
4. Edit or delete tasks by navigating to the task details page.
5. Assign tags and contributors to tasks for better organization.
6. Mark tasks as completed or uncompleted using the checkboxes.

## Configuration

The application configuration is located in the `config/app.php` file. You can customize various settings such as the application name, environment, debug mode, and URL.

## Contributing

Contributions are welcome! Please follow these steps to contribute:

1. Fork the repository.
2. Create a new branch for your feature or bugfix.
3. Commit your changes and push the branch to your fork.
4. Create a pull request with a detailed description of your changes.

## License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for more information.

## Contact

For any questions or inquiries, please contact the project maintainer @jorgeajimenezl at [jorgeajimenezl17@gmail.com](mailto:jorgeajimenezl17@gmail.com).

---

Enjoy managing your tasks with our Task Management Website!