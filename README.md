
# Missing Persons Search Application

## Introduction

The Missing Persons Search Application is a volunteer project aimed at helping locate and identify missing individuals in Israel, especially in light of the ongoing armed conflict. This application is designed to fetch vital information, such as social IDs or full names, from Gmail messages and organize it to aid in search efforts.

## Background

An ongoing armed conflict between Palestinian militant groups led by Hamas and Israel and its defense forces began on 7 October 2023, with a coordinated surprise offensive on Israel. The terrorist attack began in the morning with a barrage of at least 6,000 rockets launched from the Hamas-controlled Gaza Strip against Israel, resulting in casualties and hostages.

## How the Project Started

The inception of this project was born out of a critical need during challenging times. As a volunteer programmer, I was approached to develop this application urgently. The primary objective was to create a tool that could swiftly and effectively assist in locating missing individuals, especially during ongoing crises.

Given the urgency of the situation, speed was paramount. The focus was on functionality and reliability, with the understanding that there might be imperfections and bugs along the way. As a result, the emphasis was on delivering a solution that works correctly, even if it meant sacrificing code perfection.

I want to emphasize that this project was undertaken with the intention of offering immediate help, and as such, coding style and other conventional considerations took a backseat to functionality and accuracy.

**Your understanding of this context is appreciated as we work together to address the critical issue of locating missing persons.**

**If you encounter any issues or have suggestions for improvements, please feel free to contribute. Your support can make a significant difference in the success of this project.**

**Thank you for your understanding and commitment to the cause.**

## Project Overview

- **Functionality**: The application can extract essential information, such as social IDs or full names, from Gmail messages. It then creates local folders named after these parameters and uploads images of the individuals to their respective folders. Additionally, it can identify attached zip files, extract their contents, and upload them to the corresponding person's folder.

- **Objective**: The primary objective of this project is to assist organizations like "Needarim" (in Hebrew) or "Missing People" https://needarim.org.il/ in their efforts to locate missing individuals and provide support to their families during these challenging times.

## How the Project Works

The Missing Persons Search Application is designed to facilitate the search for missing individuals in Israel by leveraging Gmail as a communication platform. Here's an overview of how the project operates:

1. **Red Alert Gmail Inbox**: We've set up a dedicated Gmail inbox, known as the "Red Alert Gmail Inbox," to serve as a central hub for receiving information about missing persons. Anyone can send an email to this inbox with details about a missing person.

2. **Information Inclusion**: In the email, individuals can provide crucial information in Hebrew, such as the social ID (תעודת זהות) or the full name (שם מלא) of the missing person. Additionally, users can attach images of the missing individual or send zip files containing images for identification.

3. **Data Extraction and Structuring**: The application excels at extracting and structuring the received data. It processes the email contents to identify the social ID, full name, and any attached images. This automation ensures that the information is organized for further analysis.

4. **Facial Recognition AI**: The structured data, including images, is then forwarded for in-depth investigation. One of the key tools utilized in the investigation process is facial recognition AI. This advanced technology assists in verifying and identifying missing individuals, contributing to their potential recovery.

5. **Further Investigation**: The collected data is invaluable for organizations and agencies involved in locating missing persons. It aids in their efforts to reunite families with their loved ones, especially during challenging times.

By utilizing the Red Alert Gmail Inbox and this streamlined application, we aim to harness technology for the greater good, supporting the vital mission of finding missing persons in Israel.

**Feel free to reach out if you have any questions or would like to contribute to this meaningful project.**


# Getting Started

To begin using the Missing Persons Search Application and integrate it with your Gmail account, follow these steps:

### Prerequisites

Before you can use this application, you'll need to set up the required credentials and permissions. Follow these steps to get started:

## Setting Up Google API Credentials

To use this application, you'll need to set up Google API credentials in a `credentials.json` file. Follow these steps to create and configure the `credentials.json` file:

1. Visit the [Google Cloud Console](https://console.cloud.google.com/).

2. Create a new project or select an existing one.

3. Navigate to the **APIs & Services > Credentials** page.

4. Click on the **Create Credentials** dropdown and select **OAuth client ID**.

5. Choose "Web application" as the application type.

6. In the "Authorized JavaScript origins" section, add `http://localhost:8080` as an authorized origin if it's not already there.

7. In the "Authorized redirect URIs" section, add `http://localhost:8080/gmail/gmail.php` as an authorized redirect URI.

8. Click the **Create** button.

9. After creating the OAuth client ID, click the download button (down arrow) to download the JSON file containing your credentials. This file should be named `credentials.json`.

10. Place the `credentials.json` file in the root directory of your project.

Your `credentials.json` file should look something like this (with your own client ID and client secret):

```json
{
  "web": {
    "client_id": "YOUR_CLIENT_ID_HERE",
    "project_id": "extractmediafromgmail",
    "auth_uri": "https://accounts.google.com/o/oauth2/auth",
    "token_uri": "https://oauth2.googleapis.com/token",
    "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
    "client_secret": "YOUR_CLIENT_SECRET_HERE"
  }
}



2. **Clone This Repository**:

   Clone this GitHub repository to your local machine:

   ```bash
   git clone https://github.com/yourusername/your-repo-name.git


## Contributions

Contributions to this project are welcome! If you have ideas for improvements or bug fixes, feel free to create a pull request.

## License

This project is licensed under the [MIT License](LICENSE).

---

*Note: This README provides an overview of the project. For detailed setup instructions and usage guidelines, please refer to the project's documentation.*

