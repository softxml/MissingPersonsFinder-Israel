
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

## Getting Started

To use this application, follow these steps:

1. Clone the repository to your local machine.

2. Configure your Gmail credentials and API access by following the setup instructions in the project's documentation.

3. Run the application to start fetching and organizing information from your Gmail inbox.

## Contributions

Contributions to this project are welcome! If you have ideas for improvements or bug fixes, feel free to create a pull request.

## License

This project is licensed under the [MIT License](LICENSE).

---

*Note: This README provides an overview of the project. For detailed setup instructions and usage guidelines, please refer to the project's documentation.*

