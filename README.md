
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

1. **Create a Google Cloud Project and OAuth 2.0 Credentials**:

   To access Gmail and fetch information, you'll need to set up a Google Cloud Project and OAuth 2.0 credentials. This will allow your application to authenticate and access Gmail data.

   - Go to the [Google Cloud Console](https://console.cloud.google.com/).
   - Sign in with your Google account or create one if you don't have one.
   - Create a new project with a descriptive name for your application.
   - Enable the Gmail API for your project in the "APIs & Services" > "Library" section.
   - Create OAuth 2.0 credentials in the "APIs & Services" > "Credentials" section.
   - Configure the OAuth consent screen and specify the redirect URI for your application.
   - Once created, note down the client ID and client secret provided. You'll use these credentials in your application.

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

