🎵 Automated Music Organizer (AMO)
Automated Music Organizer is a lightweight, PHP-based utility designed to clean up chaotic music directories. It recursively scans a source folder, analyzes audio metadata (ID3 tags), and dynamically moves files into a structured directory hierarchy based on the Artist Name.

🚀 Features
Recursive Processing: Scans subdirectories deeply to find every audio file.

ID3 Metadata Analysis: Uses the getID3 library to extract high-accuracy artist information (supports ID3v1 and ID3v2).

Path Sanitization: Automatically scrubs illegal characters (\ / : * ? " < > |) from metadata to ensure OS-level path compatibility.

Real-time Logging: A built-in "Terminal" UI that provides live feedback on file operations and IO errors.

Cross-Platform Defaults: Smart-detection of Windows USERPROFILE to set default Desktop paths.

🛠️ Technical Stack
Backend: PHP 7.4+

Metadata Engine: James Heinrich - getID3

Frontend: Clean CSS3 with a "Modern Dark" terminal aesthetic.

IO Operations: Utilizes RecursiveDirectoryIterator for memory-efficient file system crawling.

📋 Installation Guide
1. Prerequisites
Ensure you have PHP 7.4 or higher installed and Composer available on your system.

2. Clone the Repository
Bash
git clone https://github.com/yourusername/automated-music-organizer.git
cd automated-music-organizer
3. Install Dependencies
Run the following command to pull the required metadata libraries:

Bash
composer require james-heinrich/getid3
4. Web Server Setup
Point your local server (Apache/Nginx/Built-in) to the project directory.
Quick Start (PHP Built-in Server):

Bash
php -S localhost:8000
Then visit http://localhost:8000 in your browser.

📖 User Guide
Define Source: Input the path where your unsorted .mp3 or .wav files currently sit.

Define Target: Input the path where you want the organized library to be built.

Run Engine: Click "Run Organizer Engine".

Review Logs: Check the "Terminal" window at the bottom for a breakdown of every file moved. If a file is currently open in a player (like VLC), the system will flag an "IO Error" instead of crashing.

📂 Project Structure
Plaintext
├── vendor/               # Composer dependencies (getID3)
├── index.php             # Core logic and UI dashboard
├── logo.png              # System branding
├── composer.json         # Dependency management
└── README.md             # Documentation
🛡️ Engineering Notes
Path Sanitization Logic
The system uses the following regex to ensure that artists with "illegal" names (like AC/DC) do not break the file system creation:

PHP
$safeArtistName = preg_replace('#[\\\/:\*\?"<>\|]#', '-', $artist);
Directory Permissions
The system attempts to create folders with 0755 permissions. Ensure the user running the PHP process (e.g., www-data or your local user) has write access to both the source and target directories.

🤝 Contributing
Fork the Project.

Create your Feature Branch (git checkout -b feature/AmazingFeature).

Commit your Changes (git commit -m 'Add some AmazingFeature').

Push to the Branch (git push origin feature/AmazingFeature).

Open a Pull Request.

📜 License
Distributed under the MIT License. See LICENSE for more information.

Developed by JM ESCOBAR

System Version: 1.0.5