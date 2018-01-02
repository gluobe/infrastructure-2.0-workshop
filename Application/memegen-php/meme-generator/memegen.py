__author__ = "Naeem Hasan (https://github.com/naeem-hasan/meme-generator-python/blob/master/memegen.py), adapted by Ben Leynen @ Gluo"
__version__ = "0.1"
# Usage: python memegen.py "Top line" "Bottom line" originalphoto.jpg targetphoto.jpg

import sys
if (sys.version_info > (3, 0)):
    print "The script requires Python 2 :( Sorry!"
    sys.exit()

from os import system, mkdir, getcwd, name
from os.path import exists, join
from textwrap import wrap

try:
    from wand.drawing import Drawing
    from wand.image import Image
    from wand.color import Color
except:
    print "Error importing Wand! Try installing it with 'pip install wand'"
    sys.exit()

MEME_FOLDER = "memes"
TEMPLATES_FOLDER = "templates"
MARGINS = [50, 130, 200, 270, 340]

if not exists(join(getcwd(), MEME_FOLDER)):
    mkdir(join(getcwd(), MEME_FOLDER))
    
if not exists(join(getcwd(), TEMPLATES_FOLDER)):
    mkdir(join(getcwd(), TEMPLATES_FOLDER))

def get_warp_length(width):
    return int((33.0 / 1024.0) * (width + 0.0))

def generate_meme(upper_text, lower_text, picture_name_orig, picture_name_target):
    main_image = Image(filename=join(TEMPLATES_FOLDER, picture_name_orig))
    main_image.resize(1024, int(((main_image.height * 1.0) / (main_image.width * 1.0)) * 1024.0))

    upper_text = "\n".join(wrap(upper_text, get_warp_length(main_image.width))).upper()
    lower_text = "\n".join(wrap(lower_text, get_warp_length(main_image.width))).upper()
    lower_margin = MARGINS[lower_text.count("\n")]

    text_draw = Drawing()

    text_draw.font = join(getcwd(), "impact.ttf")
    text_draw.font_size = 70
    text_draw.text_alignment = "center"
    text_draw.stroke_color = Color("black")
    text_draw.stroke_width = 3
    text_draw.fill_color = Color("white")

    if upper_text:
        text_draw.text(main_image.width / 2, 80, upper_text)
    if lower_text:
        text_draw.text(main_image.width / 2, main_image.height - lower_margin, lower_text)

    text_draw(main_image)

    main_image.save(filename=join(getcwd(), MEME_FOLDER, picture_name_target))

if __name__ == "__main__":
    argv = sys.argv
    if len(argv) < 5:
        print "Not enough arguments! (python memegen.py 'Top line' 'Bottom line' originalphoto.jpg targetphoto.jpg)"
        sys.exit()
    else:
        generate_meme(argv[1], argv[2], argv[3], argv[4])
