# User Data Management System

This project is a web-based user data management system built with PHP and MySQL. It provides a backend interface for managing user accounts and various types of personal data, such as experience, education, awards, projects, patents, speeches, teaching materials, papers, and courses. The system is designed for multi-user environments, with each user only able to manage their own data.

## Features

- **User Authentication**: Secure login/logout system with password hashing.
- **User Management**: Admin interface for adding, editing, and listing user accounts.
- **Personal Data Modules**: Each user can manage their own:
  - Experience
  - Education
  - Awards
  - Projects
  - Patents
  - Speeches
  - Teaching Materials
  - Papers
  - Courses
- **Permission Control**: Users can only view and edit their own data. All data tables are linked to the user's ID.
- **Responsive UI**: Simple and clean interface for easy management.

## File Structure

- `login.php`, `logout.php`: User authentication
- `user_manage.php`: User account management
- `*_add.php`, `*_edit.php`, `*_delete.php`, `*_search.php`, `*_view.php`: CRUD operations for each data module
- `db.php`: Database connection
- `dashboard.php`: Main backend dashboard

## Setup Instructions

1. **Environment**: Requires PHP (7.x or above), MySQL, and a web server (e.g., XAMPP).
2. **Database**: The system will automatically create necessary tables if they do not exist.
3. **Configuration**: Update database credentials in `db.php` if needed.
4. **Usage**:
   - Access `login.php` to log in.
   - Use the dashboard to manage your personal data.

## Security Notes
- Passwords are securely hashed using PHP's `password_hash` and `password_verify`.
- All data operations are protected by session authentication.
- Users can only access and modify their own records.

## License
This project is for educational and internal use. Please modify and extend as needed for your organization.
