import os
from bs4 import BeautifulSoup

activate_path = 'C:\\wamp64\\www\\gmail\\venv\\Scripts\\activate_this.py'
exec(open(activate_path).read(), {'__file__': activate_path})

import sys
sys.path.append('C:\\Users\\User\\AppData\\Roaming\\Python\\Python39\\site-packages\\')
import codecs
from transformers import pipeline

sys.stdout = open(1, 'w', encoding='utf-8', closefd=False)

def extract_names_from_text(text):
    # Initialize the NER pipeline using the specified model and tokenizer
    NER = pipeline(
        "token-classification",
        model="avichr/heBERT_NER",
        tokenizer="avichr/heBERT_NER",
    )

    # Use the NER pipeline on the input text
    result = NER(text)

    names = []
    current_name = []

    for entity in result:
        word = entity['word'].replace("##", "")
        if entity['entity'] == 'B_PERS' and not any(char.isdigit() for char in word):
            if current_name:
                names.append(" ".join(current_name))
                current_name = []
            current_name.append(word.replace(" ", ""))
        elif entity['entity'] == 'I_PERS':
            current_name.append(word.replace(" ", ""))

    if current_name:
        names.append(" ".join(current_name))

    # Check if any full names were found, return None if not
    if not names:
        return None
    else:
        # Join the extracted full names with hyphens
        return "_".join(names)

# Get the input text
input_text = sys.argv[1]

if os.path.isfile(input_text):
    # If it's a file path, read the file's content and process it
    with open(input_text, 'r', encoding='utf-8') as file:
        file_content = file.read()
        soup = BeautifulSoup(file_content, "html.parser")
        text = soup.get_text()
else:
    # If it's not a file path, treat it as a string
    text = input_text

extracted_names = extract_names_from_text(text)

if extracted_names is not None:
    print(extracted_names)
else:
    print("None")
