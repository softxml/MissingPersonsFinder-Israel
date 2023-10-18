import sys
import re
import os





def validate_mispar_zehut(mispar_zehut):
    # Remove any non-digit characters
    mispar_zehut = ''.join(filter(str.isdigit, mispar_zehut))

    # Check if the length is exactly 9 digits
    if len(mispar_zehut) != 9:
        return False

    # Calculate the checksum
    checksum = 0
    for i in range(9):
        digit = int(mispar_zehut[i])
        multiplier = 1 if i % 2 == 0 else 2
        product = digit * multiplier
        checksum += product - 9 if product > 9 else product

    # Check if the checksum is divisible by 10
    if checksum % 10 != 0:
        return False

    return True





def find_mispar_zehut(text):
    url_pattern = r'https?://\S+'

    # Find and replace URLs with an empty string
    text_without_urls = re.sub(url_pattern, '', text)

    # This pattern looks for a standalone 5 to 9 digit sequence not preceded or followed by a hyphen or space
    pattern = r'\b\d{5,9}\b(?![^<]*>)'
    matches = re.findall(pattern, text_without_urls)

    valid_matches = []
    for match in matches:
        if validate_mispar_zehut(match):
            valid_matches.append(match)
    
    if not valid_matches:
        return None

    return valid_matches

            

def process_input(input_text):
    result = find_mispar_zehut(input_text)

    if result is not None:
        # Print the found ID numbers separated by "-"
        print("-".join(result))
    else:
        return None

# Check if there is at least one command-line argument

    # Get the first command-line argument
argument = sys.argv[1]

    # Check if the argument is a valid file path
if os.path.isfile(argument):
        # If it's a file path, read the file's content and process it
    with open(argument, 'r', encoding='utf-8') as file:
        file_content = file.read()
        # soup = BeautifulSoup(file_content, "html.parser")
        # text = soup.get_text()
        process_input(file_content)
else:
        # If it's not a file path, treat it as a string and process it
    process_input(argument)
