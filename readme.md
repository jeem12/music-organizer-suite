# 🎵 Automated Music Organizer (AMO)

![Version](https://shields.io)
![PHP](https://shields.io)
![License](https://shields.io)

**Automated Music Organizer** is a lightweight, PHP-based utility designed to transform chaotic music directories into structured libraries. It recursively scans folders, analyzes ID3 metadata, and dynamically organizes files into a clean hierarchy based on the **Artist Name**.

---

## 🚀 Features

*   **Recursive Processing:** Deeply scans subdirectories to locate every audio file.
*   **ID3 Metadata Analysis:** Powered by the `getID3` library for high-accuracy artist extraction (supports ID3v1 & ID3v2).
*   **Path Sanitization:** Automatically scrubs illegal characters (`\ / : * ? " < > |`) to ensure OS-level compatibility (e.g., converting "AC/DC" to "AC-DC").
*   **Real-time Logging:** A built-in "Modern Dark" terminal UI provides live feedback on file operations and IO errors.
*   **Cross-Platform Defaults:** Smart detection of Windows `USERPROFILE` to streamline path selection.

---

## 🛠️ Technical Stack

- **Backend:** PHP 7.4+
- **Metadata Engine:** [James Heinrich - getID3](https://github.com)
- **Frontend:** CSS3 "Modern Dark" Terminal Aesthetic
- **IO Operations:** `RecursiveDirectoryIterator` for memory-efficient filesystem crawling.

---

## 📋 Installation Guide

### Prerequisites
*   PHP 7.4 or higher installed.
*   [Composer](https://getcomposer.org) installed on your system.

### 1. Clone the Repository
```bash
git clone https://github.com
cd automated-music-organizer
```

** 2. Clone the Repository**
```bash
git clone https://github.com
cd automated-music-organizer
```
** 3. Install Dependencies

Run the following command to pull the required metadata libraries:
```bash
composer require james-heinrich/getid3
```

**4. Web Server Setup
Point your local server (Apache/Nginx/Built-in) to the project directory.
Quick Start (PHP Built-in Server):

```bash
php -S localhost:8000
```

>Then visit `http://localhost:8000` in your browser.

 ### User Guide
- Define Source: Input the path where your unsorted .mp3 or .wav files currently sit.
- Define Target: Input the path where you want the organized library to be built.
- Run Engine: Click "Run Organizer Engine".
- Review Logs: Check the "Terminal" window at the bottom for a breakdown of every file moved.
   -- Note: If a file is currently open in a player (like VLC), the system will flag an "IO Error" instead of crashing.
