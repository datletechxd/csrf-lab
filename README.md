# CSRF Lab

This is a demo about Cross-Site Request Forgery (CSRF) attacks.

## Requirements

- Install XAMPP
- Run Apache and MySQL

## Installation and Running

1.  **Install XAMPP**: Download and install [XAMPP](https://www.apachefriends.org/index.html).
2.  **Start Apache and MySQL** from the XAMPP Control Panel.
3.  **Place the project in the htdocs folder**:
    ```sh
    cd C:\xampp\htdocs
    git clone https://github.com/datletechxd/csrf-lab.git
    ```
4.  **Access the web application**: Open a browser and visit:
    ```
    http://localhost:<port>/csrf-lab/
    ```

## Structure CSRF Lab

- **attack_site (Attacker)**: This version is tasked with attacking site defenses to test CSRF.
- **victim_site (Vulnerable)**: This version does not have CSRF protection, making it possible to execute unauthorized requests using a crafted attack page.
- **secure_site (Secured)**: This version includes CSRF protection by implementing CSRF tokens.
