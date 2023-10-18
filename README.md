
# Missing Persons Search Application

## Introduction

The Missing Persons Search Application is a volunteer project aimed at helping locate and identify missing individuals in Israel, especially in light of the ongoing armed conflict. This application is designed to fetch vital information, such as social IDs or full names, from Gmail messages and organize it to aid in search efforts.

## Background

An ongoing armed conflict between Palestinian militant groups led by Hamas and Israel and its defense forces began on 7 October 2023, with a coordinated surprise offensive on Israel. The terrorist attack began in the morning with a barrage of at least 6,000 rockets launched from the Hamas-controlled Gaza Strip against Israel, resulting in casualties and hostages.

## Project Overview

- **Functionality**: The application can extract essential information, such as social IDs or full names, from Gmail messages. It then creates local folders named after these parameters and uploads images of the individuals to their respective folders. Additionally, it can identify attached zip files, extract their contents, and upload them to the corresponding person's folder.

- **Objective**: The primary objective of this project is to assist organizations like "Needarim" (in Hebrew) or "Missing People" in their efforts to locate missing individuals and provide support to their families during these challenging times.

## How It Works

1. **Gmail Integration**: The application connects to your Gmail account and iterates through your inbox.

2. **Information Extraction**: It extracts vital information, such as social IDs or full names, from email subjects and bodies.

3. **Folder Creation**: The application creates local folders with names based on the extracted information.

4. **Image Upload**: It identifies and uploads images of the individuals to their respective folders.

5. **Zip File Handling**: If there are attached zip files, the application extracts their contents and uploads them to the corresponding person's folder.

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

