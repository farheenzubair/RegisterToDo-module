
# RegisterToDo-module
WordPress plugin for user login, registration, and to-do list management.

## Motivation

As an intern at a software house, I developed a custom WordPress plugin for managing to-do lists with secure user registration and login features. This project enhanced my skills in PHP, JavaScript, and MySQL while deepening my understanding of WordPress's plugin architecture. It provided valuable insights into real-world development within a professional environment.

## Video Tutorials

[RegisterToDo video](https://drive.google.com/file/d/1bhXWjJzlNQOEHFMob7EcsENJ8WV-dvca/view?usp=sharing)

[Rest API Video]()

[WP CLI Commands Video](https://drive.google.com/file/d/1VbCrYIZpGw9ei6OxzMhkQ03YL48srMwS/view?usp=sharing)

## Code Style

This plugin follows standard WordPress coding practices to ensure readability, maintainability, and compatibility with other WordPress plugins and themes. 
Key aspects of the code style include:

- **PHP Coding Standards:** [WordPress PHP Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/php/). 

- **HTML & CSS:** The HTML structure adheres to semantic HTML5 standards, and CSS is organized using a modular approach. CSS naming follows the [BEM (Block, Element, Modifier)](http://getbem.com/introduction/) methodology where appropriate.

- **JavaScript:**  [WordPress JavaScript Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/javascript/). 

- **Security Best Practices:** The plugin code is written with security in mind, including proper data sanitization, validation, and escaping to prevent vulnerabilities such as SQL injection and XSS.

- **Version Control:** The project is maintained under version control using Git, with clear commit messages and branching strategies to ensure a clean development process.


## Tech/Framework Used

This Custom To-Do List Plugin leverages several technologies and frameworks to ensure robust functionality and seamless integration with WordPress. The key technologies used in this project include:

- **WordPress:** The plugin is built for WordPress, utilizing its rich set of APIs and hooks to integrate with the WordPress ecosystem.

- **PHP:** The server-side scripting language used to create the core functionalities of the plugin. It adheres to WordPress PHP Coding Standards for consistency and readability.

- **JavaScript:** Employed to enhance user interactions and manage dynamic content on the client side. JavaScript is used in combination with jQuery to provide a responsive and interactive user experience.

- **jQuery:** A JavaScript library that simplifies HTML document traversal, event handling, and animation, used to streamline DOM manipulations and AJAX requests.

- **AJAX:** Asynchronous JavaScript and XML (AJAX) is used for dynamic content updates and interactions without requiring a full page reload, providing a smoother and more interactive user experience.

- **REST API:** The WordPress REST API is utilized to enable communication between the front-end and back-end, facilitating data retrieval and updates in a structured manner.

- **HTML5:** Used for structuring the plugin’s user interface, ensuring semantic and accessible HTML markup.

- **CSS3:** Applied for styling the plugin’s front-end interface, using modern CSS techniques for a clean and user-friendly design.

- **WordPress Shortcodes API:** Allows for embedding plugin functionality into posts, pages, or widgets via shortcodes.

## Features

### User Registration and Login
- **Registration Form:** Users can register with their name, email, and password. Duplicate users are detected, and appropriate messages are displayed.
- **Login Form:** Users can log in with their credentials. Successful login redirects to the To-Do List page.

### To-Do List Management
- **Add Tasks:** Create new tasks with titles.
- **Mark Tasks as Complete:** Update task status to completed.

### User-Specific Task Management
- **Personalized Lists:** Each user has their own to-do list.
- **Individual Task Views:** Ensure privacy with user-specific tasks.

### Admin Features
- **User Management:** Admins can view and manage all users and their tasks.
- **Task Overview:** Admins get a comprehensive view of tasks across the platform.

### Nonce Verification
- **Security Measures:** Utilizes nonce verification to secure form submissions and AJAX requests.

### AJAX Integration
- **Real-Time Updates:** AJAX enables task operations without page reloads.
- **Seamless User Experience:** Provides instant feedback and improved interaction.

### WP-CLI Commands

This plugin includes custom WP-CLI commands to manage tasks from the command line interface.

#### Available Commands

##### `wp todo add_task`

- **Description:** Adds a new task to the specified user's to-do list. If no user ID is provided, the task will be added to the currently logged-in user's list.
- **Usage:** `wp todo add_task "Task Description" [--user=<user_id>]`
- **Example:**
  ```bash
  wp todo add_task "Complete project report" --user=1

##### `wp todo fetch_task`

- **Description:** Fetches and displays the to-do tasks for the specified user. If no user ID is provided, it fetches tasks for the currently logged-in user.
- **Usage:** `wp todo fetch_tasks [<user_id>]`
- **Example:**
  ```bash
  wp todo fetch_tasks --user=1


##### `wp todo update_task`

- **Description:** Updates the status of a specified task for the currently logged-in user.
- **Usage:** `wp todo update_task <task_id> --status=<status> [<user_id>]`
- **Example:**
  ```bash
  wp todo update_task 1-66d15d7036b5f --status=completed --user=1

#### Requirements

- **WP-CLI:** Ensure WP-CLI is installed on your system. You can find installation instructions and download WP-CLI from [wp-cli.org](https://wp-cli.org/).

#### Usage

1. **Open your terminal or command line interface.**
2. **Navigate to the WordPress root directory.** 
3. **Run the desired WP CLI commands.**


## Installation

1. Download the plugin ZIP file.
2. In your WordPress admin panel, go to **Plugins > Add New**.
3. Click **Upload Plugin** and choose the downloaded ZIP file.
4. Click **Install Now** and then **Activate**.


## Contact

For any questions or feedback, please contact at [farheenzubair810@gmail.com].