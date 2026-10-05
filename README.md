# Job Portal System

A web-based online recruitment and online interview system designed for organizations in Cameroon.

## 📌 Overview

The **Job Portal System** is a web application that helps organizations publish job opportunities online and allows applicants to discover available positions, submit applications, and participate in scheduled online interviews.

The project was developed to address challenges associated with traditional recruitment methods, where job opportunities may be communicated through posters, paper notices, or other offline methods.

## 🎯 Problem Statement

Traditional recruitment processes can make it difficult for applicants to discover job opportunities and submit their applications conveniently.

Organizations may also face challenges managing large numbers of applications using paper-based processes.

This system provides a centralized digital platform that brings organizations and applicants together while supporting the recruitment process from job posting to application management and online interviews.

## ✨ Key Features

### 👤 Applicant Features

- Create and manage an applicant account
- Browse available job opportunities
- View detailed job information
- Search and explore available positions
- Submit job applications online
- Upload CV and supporting documents
- Upload identification documents when required
- Save interesting job opportunities
- Track application status
- Communicate through the messaging system
- Receive notifications
- Reset account password
- Participate in scheduled online interviews

### 🏢 Organization Features

- Create and manage organization accounts
- Publish job opportunities
- Add job descriptions, salary information, location, category, and requirements
- Manage published jobs
- View submitted applications
- Review applicant information and documents
- Manage recruitment activities
- Communicate with applicants
- Schedule online interviews

### 🛠️ Administration

- Administrative dashboard
- Manage system users and organizations
- Manage recruitment-related information
- Monitor system activities

## 💻 Technologies Used

- **PHP** — Backend development
- **MySQL / MariaDB** — Database management
- **HTML5** — Page structure
- **CSS3** — Styling and responsive layouts
- **JavaScript** — Client-side functionality
- **XAMPP** — Local development environment
- **Git & GitHub** — Version control and project management
- **VS Code** — Development environment

## 🗄️ Database

The system uses a relational database to manage recruitment data.

Some of the main entities include:

- Applicants
- Organizations
- Jobs
- Applications
- Application Documents
- Interviews
- Messages
- Notifications
- Saved Jobs
- Documents
- Salaries
- Company Reviews
- Contact Messages
- Administrators

## 📂 Project Structure

```text
job/
├── admin.php
├── dashboard_applicant.php
├── home.php
├── jobs.php
├── login_applicant.php
├── login_org.php
├── message.php
├── reset_password.php
├── uploads/
├── .gitignore
└── ...
```

> User-uploaded files in the `uploads/` directory are excluded from the Git repository for privacy and security.

## ⚙️ Running the Project Locally

### Requirements

Make sure you have:

- XAMPP
- PHP
- MySQL or MariaDB
- A web browser
- Git
- VS Code or another code editor

### Installation

1. Clone the repository:

```bash
git clone https://github.com/esperancedivinelonlamassoh-ux/job-portal-system.git
```

2. Move the project into the XAMPP `htdocs` directory:

```text
C:\xampp\htdocs\job
```

3. Start **Apache** and **MySQL** from XAMPP.

4. Create the required database in **phpMyAdmin**.

5. Configure the database connection in the project according to your local MySQL credentials.

6. Open the application in your browser:

```text
http://localhost/job/
```

## 🔐 Security Considerations

The project includes authentication and password-reset functionality.

Sensitive user-uploaded documents are intentionally excluded from version control using `.gitignore`.

For production deployment, additional security measures should be implemented, including:

- Strong password hashing
- Input validation and sanitization
- Secure file-upload validation
- CSRF protection
- Secure session management
- HTTPS
- Environment-based configuration for sensitive credentials

## 🚀 Future Improvements

Possible future improvements include:

- REST API integration
- Improved online interview integration
- Email notifications
- Advanced applicant search and filtering
- Improved organization verification
- Enhanced security
- Cloud deployment
- Mobile application
- AI-assisted recruitment features

## 📚 Project Purpose

This project was developed as a practical software engineering project to demonstrate skills in:

- Web application development
- Backend development with PHP
- Relational database design
- Authentication and authorization
- File handling
- Recruitment workflow design
- Version control with Git
- Software development practices

## 👨‍💻 Author

**Lonla Massoh Esperance Divine**

Software Engineering Student | Web & Backend Developer

GitHub:  
https://github.com/esperancedivinelonlamassoh-ux
```

### After saving the file

Go back to your PowerShell terminal and run **only this first**:

```powershell
git status
```

Send me the result. Then we'll commit the new README and push it to GitHub. 🚀