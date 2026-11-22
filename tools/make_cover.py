#!/usr/bin/env python3
"""GTMLens house cover: 1200x675 PNG in the procedural navy/violet/pink style.

Usage: python3 tools/make_cover.py OUTPUT.png YEAR MONTH
The node layout is seeded on YEAR*100+MONTH, so each month gets a stable, distinct cover.
"""
import random
import sys

from PIL import Image, ImageDraw, ImageFont

W, H = 1200, 675
NAVY = (13, 31, 60)
PALETTE = [(76, 29, 149), (109, 40, 217), (236, 72, 153)]
FONT = "/System/Library/Fonts/Helvetica.ttc"


def lerp(a, b, t):
    return tuple(int(a[i] + (b[i] - a[i]) * t) for i in range(3))


def wash_colour(x, y):
    t = x / W * 0.7 + y / H * 0.3
    if t < 0.6:
        return lerp(PALETTE[0], PALETTE[1], t / 0.6)
    return lerp(PALETTE[1], PALETTE[2], min(1.0, (t - 0.6) / 0.4))


def make_cover(out_path, year, month):
    rng = random.Random(year * 100 + month)
    img = Image.new("RGBA", (W, H), NAVY + (255,))

    wash = Image.new("RGBA", (W, H))
    px = wash.load()
    for y in range(H):
        for x in range(W):
            px[x, y] = wash_colour(x, y) + (int(255 * 0.55),)
    img = Image.alpha_composite(img, wash)

    overlay = Image.new("RGBA", (W, H), (0, 0, 0, 0))
    d = ImageDraw.Draw(overlay)
    r = 1.6
    for y in range(0, H, 44):
        for x in range(0, W, 44):
            d.ellipse((x - r, y - r, x + r, y + r), fill=(255, 255, 255, int(255 * 0.10)))

    for _ in range(rng.randint(9, 13)):
        cx, cy = rng.randint(60, W - 60), rng.randint(60, H - 120)
        core = rng.randint(6, 14)
        colour = rng.choice(PALETTE)
        for ring, alpha in ((core * 4, 40), (core * 2.5, 70)):
            d.ellipse((cx - ring, cy - ring, cx + ring, cy + ring),
                      outline=(255, 255, 255, alpha), width=2)
        d.ellipse((cx - core, cy - core, cx + core, cy + core), fill=colour + (255,))
    img = Image.alpha_composite(img, overlay)

    band = Image.new("RGBA", (W, H), (0, 0, 0, 0))
    bd = ImageDraw.Draw(band)
    bd.rectangle((0, H - 64, W, H), fill=NAVY + (int(255 * 0.55),))
    bold = ImageFont.truetype(FONT, 22, index=1)
    regular = ImageFont.truetype(FONT, 22, index=0)
    ty = H - 64 + (64 - 22) // 2
    bd.text((36, ty), "GTMLENS", font=bold, fill=(255, 255, 255, 255))
    bd.text((160, ty), "· INDEPENDENT ANALYST VIEW", font=regular, fill=(180, 186, 200, 255))
    img = Image.alpha_composite(img, band)

    img.convert("RGB").save(out_path, "PNG")


if __name__ == "__main__":
    if len(sys.argv) != 4:
        sys.exit("usage: make_cover.py OUTPUT.png YEAR MONTH")
    make_cover(sys.argv[1], int(sys.argv[2]), int(sys.argv[3]))
    print(sys.argv[1])
