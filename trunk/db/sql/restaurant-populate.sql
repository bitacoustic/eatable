# Populates the Restaurants table with test data.

use eatable;

INSERT INTO Restaurants(name, address_street, address_city, address_state, address_postal_code, phone_number, keywords) Values("Crepevine", "624 Irving Street", "San Francisco", "CA", 94122, 4156815858, "salad, burger, crepes, eggs");
INSERT INTO Restaurants(name, address_street, address_city, address_state, address_postal_code, phone_number, keywords) Values("Pasquale's Pizza", "700 Irving Street","San Francisco", "CA", 94122, 4156612140, "pizza, italian");
INSERT INTO Restaurants(name, address_street, address_city, address_state, address_postal_code, phone_number, keywords) Values("The Cliff House", "1090 Point Lobos Avenue", "San Francisco", "CA", 94121, 4153863330,"fine dining, burgers, bar, drinks");
INSERT INTO Restaurants(name, address_street, address_city, address_state, address_postal_code, phone_number, keywords) Values("Mel's Dinner", "3355 Geary Blvd","San Francisco", "CA", 94109, 4153872244, "burgers, shakes, diner");

INSERT INTO Images(image) Values(LOAD_FILE("/home/sebastian/public_html/eatable/trunk/db/img/crepevine.jpg"));
INSERT INTO Images(image) Values(LOAD_FILE("/home/sebastian/public_html/eatable/trunk/db/img/pasquales.jpg"));
INSERT INTO Images(image) Values(LOAD_FILE("/home/sebastian/public_html/eatable/trunk/db/img/cliffhouse.jpg"));
INSERT INTO Images(image) Values(LOAD_FILE("/home/sebastian/public_html/eatable/trunk/db/img/melsdiner.jpg"));
