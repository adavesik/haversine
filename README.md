# Haversine formula in PHP
PHP implementation of Haversine formula

# Main functionality

1. user can type address (ex. Толстопальцево 28 or 28 Толстопальцево or 28 Толстопальцево str...)
2. system recognizes this address and brings similar addresses to client for choosing the correct one (ex. if search string is Толстопальцево 2, system suggest to user 2 variants: 1. Толстопальцево 28, 2. Толстопальцево 2), then , after selecting  correct one, system brings the table with DISTANCES (in KM) from client's address to all other addresses divided in 3 groups.

below you can find example

| Distance < 5 Km        | Distance From 5 Km to 30 Km           | Distance more than 30 Km  |
| ------------- |:-------------:| -----:|
| Толстопальцево 28 (0.2 km)      | Анадырский пр. 9 (6.7 km) | Кольская ул. 8 (28 km) |
| Толстопальцево 25 (0.4 km)      | Анадырский пр. 19 (9.1 km)      |   Какая-то ул. 28 (38 km) |
| Толстопальцево 22 (0.9 km) | Анадырский пр. 32 (9.5 km)      |    ... |
