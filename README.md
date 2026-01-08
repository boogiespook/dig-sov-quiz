# Viewfinder - Digital Sovereignty Quiz

An interactive web-based quiz application designed to assess organizational understanding of digital sovereignty and vendor autonomy across critical infrastructure domains.

## Overview

The Digital Sovereignty Quiz evaluates knowledge across 7 key domains with 21 randomized true/false questions. Upon completion, participants receive a detailed scorecard and can generate a personalized certificate.

## Features

- **21 Question Assessment**: Covers 7 critical domains of digital sovereignty
- **Randomized Experience**: Questions and domains are shuffled on each session
- **Progressive Navigation**: Step-by-step interface with validation
- **Instant Feedback**: Detailed explanations for each answer
- **Scoring System**: Three-tiered readiness levels (Foundation, Strategic, Advanced)
- **Certificate Generation**: Downloadable/printable certificates with unique IDs
- **Responsive Design**: Mobile-friendly interface

## Quiz Domains

1. **Data Sovereignty** - Legal jurisdiction and data control
2. **Technical Sovereignty** - Vendor lock-in and portability
3. **Operational Sovereignty** - Service control and access management
4. **Assurance Sovereignty** - Independent verification and auditing
5. **Open Source** - Code transparency and maintainability
6. **Executive Oversight** - Strategic risk management
7. **Managed Services** - Outsourcing considerations

## Requirements

- PHP 7.0 or higher
- Web server (Apache, Nginx, etc.)
- No database required
- Modern web browser

## Installation

### Option 1: Traditional Web Server

1. Clone or download this repository to your web server directory:
```bash
cd /var/www/html
git clone <repository-url> dig-sov-quiz
```

2. Ensure the web server has read permissions for all files:
```bash
chmod -R 755 dig-sov-quiz
```

3. Access the application through your web browser:
```
http://localhost/dig-sov-quiz/
```

### Option 2: Podman Container

1. Build the container image:
```bash
podman build -t dig-sov-quiz .
```

2. Run the container:
```bash
podman run -d -p 8080:8080 --name sovereignty-quiz dig-sov-quiz
```

3. Access the application:
```
http://localhost:8080
```

4. Stop and remove the container:
```bash
podman stop sovereignty-quiz
podman rm sovereignty-quiz
```

**Container Notes:**
- The application runs on port 8080 inside the container
- Uses Red Hat UBI 10 PHP 8.3 base image
- Runs as non-root user (UID 1001)
- No persistent data storage required
- Rootless container compatible

## File Structure

```
dig-sov-quiz/
├── index.php           # Main quiz application
├── certificate.php     # Certificate generator
├── images/
│   └── viewfinder-logo.png
├── Dockerfile          # Container build configuration
├── .dockerignore       # Docker build exclusions
└── README.md           # Documentation
```

## Usage

### Taking the Quiz

1. Navigate to `index.php` in your browser
2. Answer all 3 questions in each domain (21 total)
3. Progress through 7 domains using Next/Back buttons
4. Submit to view results and detailed feedback

### Generating a Certificate

1. Complete the quiz
2. Enter your name in the certificate form
3. Click "Get Certificate" to generate a printable certificate
4. Use browser print function to save as PDF

### Readiness Levels

- **Foundation (0-33%)**: Basic understanding - requires strategic development
- **Strategic (34-66%)**: Moderate awareness - building towards maturity
- **Advanced (67-100%)**: Strong comprehension - sovereignty-ready

## Customization

### Modifying Questions

Edit the `$domains` array in `index.php` (lines 7-64):
```php
$domains = [
    "domain_key" => [
        "title" => "Domain Title",
        "questions" => [
            "q1" => [
                "s" => "Question statement",
                "a" => "true",  // or "false"
                "e" => "Explanation text"
            ]
        ]
    ]
];
```

### Changing Branding

- Replace `images/viewfinder-logo.png` with your logo
- Update color scheme in CSS variables (line 119 in `index.php`)
- Modify certificate layout in `certificate.php`

### Adjusting Score Thresholds

Edit scoring logic in `index.php` (lines 106-108):
```php
if ($final_score <= 33) { $readiness = [...]; }
elseif ($final_score <= 66) { $readiness = [...]; }
else { $readiness = [...]; }
```

## Security Notes

- User inputs are sanitized using `htmlspecialchars()`
- No data persistence - quiz results are not stored
- Certificate IDs are generated using MD5 hash with timestamp
- No authentication required for public quiz access

## Browser Compatibility

- Chrome/Edge 90+
- Firefox 88+
- Safari 14+
- Opera 76+

## License

This application is provided as-is for educational and assessment purposes.

## Support

For issues or questions about this quiz application, please contact your system administrator or the development team.

## Credits

Developed by Viewfinder for digital sovereignty education and assessment.
