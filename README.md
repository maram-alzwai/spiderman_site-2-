 SPIDER-MAN WEB APPLICATION by Maram Alzwai 4410 and Alaa Abojazia 4435

 PROJECT OVERVIEW:
The Spider-Man Web Application is a web system built using PHP and MySQL.It provides a public- facing website for visitors and a secure admin dashboard that allows administrators to
manage site content such as movies, villains.The project is designed for academic use and future scalability.

REQUIREMENTS:
1.XAMPP (Apache, MySQL, PHP)
2.A modern web browser (Chrome, Edge)
3.VS Code

INSTALLATION STEPS:
1.Copy the project folder (for example: spiderman_site2) into the htdocs directory inside XAMPP.The default path should be is C:\xampp\htdocs
2.Open the XAMPP Control Panel and start Apache and MySQL.
3.Open a browser and navigate to phpMyAdmin using localhost.
4.Create a new database named spiderman.
5.Import the provided database file (spiderman(2).sql) into the newly created database.
6.Open the website in your browser using localhost followed by the project folder name
7.The database is provided as a separate SQL export file to allow easy import using phpMyAdmin.

ADMIN LOGIN CREDENTIALS
Username: Maram Password: loliistheadmin
Username: Alaa
Password: loloistheadmin
**Additional test accounts are available inside the database**

APPLICATION FEATURES:
Public Features:
•Home page with Spider-Man theme
•About Spider-Man
•Movies section
•MCU appearances
•Villains section
•Actors section
•Gallery
•Contact Us form

Admin Features:
•Secure login system
•Admin dashboard
•Manage movies (add, edit, delete)
•Manage villains
•View contact messages
•Session-based authentication

ADMIN FUNCTIONALITY:
Each management section supports full CRUD operations such as :
•Create new records
•Read existing records
•Update records
•Delete records

Database operations are implemented using PDO with prepared statements.
API OVERVIEW:
The application provides simple JSON-based APIs to allow external access to data.

Available APIs include:
PUBLIC APIs:
Public APIs are accessible without authentication and are used to retrieve website content. Available Public APIs:
•Movies API: Provides a list of all movies, including title, release year, description, rating, and poster. Used by the public website and future client-side integrations.
•Villains API: Provides a list of villains with names, powers, first appearance, and images. Used to dynamically load villain data on the public website.

PRIVATE APIs:Private APIs are restricted and require an authenticated admin session.
They are used internally by the admin dashboard and are not accessible to normal users. Available Private APIs:
•Profile API: Handles admin profile data such as username, account details, and profile- related information .Used to display and manage the admin profile page.
•Logs API: Records and retrieves system activity logs. Used to track admin actions such as login, logout, adding content, editing records, and deleting data. Helps with monitoring, auditing, and debugging.

SECURITY FEATURES:
•Password Hashing
•Session-based authentication for admin access
•Protected admin routes
•Prepared SQL statements to prevent SQL injection
•Output escaping to prevent cross-site scripting

**NOTES**
("The project must be run using XAMPP, not Live Server.(quick little note from our experience throughout the project")
