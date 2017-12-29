import sqlite3

def get_images(db):
    cur = db.execute('select * from image order by id desc limit 20')
    return cur.fetchall()

def get_memes(db):
    cur = db.execute("SELECT * from meme order by id desc limit 20")
    return cur.fetchall()

def create_meme(db, image_id, top, bottom):
    cur = db.execute('INSERT into meme(image_id, top, bottom) \
                     values(%d, "%s", "%s")' % (image_id, top, bottom))
    db.commit()
    return cur.lastrowid

def get_image_path(db, image_id):
    cur = db.execute('SELECT name from image WHERE id=%d' % image_id)
    return cur.fetchone()[0]

def create_image(db, img_name):
    cur = db.execute('INSERT into image(name) values("%s")' % img_name)
    db.commit()
    return cur.lastrowid
