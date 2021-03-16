### Where to start
Literature and other sources for PHP developers
- in English
 - https://secure.php.net/ (for anybody, get off-line here: https://devdocs.io/php/)
 - https://www.w3schools.com/php/default.asp (good for beginners)
 - https://php7explained.com/ (best source of informations about PHP 7.x)
 - https://martinfowler.com/ (not about PHP but good reading for any developer)
 - https://github.com/paragonie/awesome-appsec (it's not only about PHP but also about applicaton security)
 - https://github.com/ziadoz/awesome-php
- in Czech
 - https://php.vrana.cz/kniha-1001-tipu-a-triku-pro-php.php
 - https://books.google.cz/books/about/N%C3%A1vrhov%C3%A9_vzory_v_PHP.html?id=eBrqCwAAQBAJ&redir_esc=y

Feel free to add yours favorite, thanks.

### Tools
- https://github.com/phpstan/phpstan (can spot a "bugs" in modern PHP code)
- https://github.com/FriendsOfPHP/PHP-CS-Fixer

Feel free to add yours favorite, thanks.


---

### Zadání
Napiště "univerzální" program, který přečte libovolně dlouhý textový soubor.
Řádek po řádku bude aplikovat uživatelské filtry a dekorátory. Výstupem programu
bude počet stejných (upravených) řádků a jejich četností.

Použijte co nejvíce vlastností moderního PHP. Doporučení:
- [Iterables](http://php.net/manual/en/language.types.iterable.php)
- [Anonymous functions](http://php.net/manual/en/functions.anonymous.php), especially [Callables](http://php.net/manual/en/language.types.callable.php)
- [Types](http://php.net/manual/en/migration70.new-features.php#migration70.new-features.scalar-type-declarations)
- And [more](http://php.net/manual/en/langref.php)

#### Bonus
Upravte program tak, aby vypisoval průběžný stav nekonečného streamu.

### Řešení
Obsahuje bonus - reporting progressu, který však správně funguje jen v UNIXových terminálech (používá se tput). Lze i nastavit frekvenci pomocí ExtensibleDecorator::setReportTimeTreshold()
Lze přidávat vícero filtrů / funkcí předávaných do metody ExtensibleDecorator::registerFilter().
Metoda registerFilter() vrací ID, pomocí kterého lze filtr zrušit metodou unregisterFilter()
Filtry lze i kompletně vymazat metodou resetFilters()
Kdybych měl víc času, rád bych doplnil:
- Validaci správného tvaru filtrů (mohlo by to jít pomocí Reflexe)
- Přidání možnosti změny reportovací funckce (částečně připraveno, inicializuje se default funkce v konstruktoru)
- Další featury nové v PHP7, zejména: null coalesce operator ??, "spaceship" operator, ...

Věci, co by se daly doplnit, ale teď mě nenapadá, jak je navrhnout:
- Podpora pro extra dlouhé řádky
- Podpora pro vícero patternů
- Podpora patternů s jiným počtem matchnutých subpatternů např. (\w)

### Příklady (bash)
```bash
php old.php example.log
```
```bash
php new.php example.log
```
Lze i pajpovat (pozor, generate-example.php je ve vyšších počtech pomalý a navíc vyžaduje composer install)
```bash
php generate-example.php 10 | php new.php
```

#### Vstupní soubor
```
[2018-03-13 12:16:10] test.DEBUG: Test message [] []
[2018-03-13 12:16:10] test.ERROR: Test message [] []
[2018-03-13 12:16:10] test.WARNING: Test message [] []
[2018-03-13 12:16:10] test.WARNING: Test message [] []
[2018-03-13 12:16:10] test.INFO: Test message [] []
[2018-03-13 12:16:10] test.NOTICE: Test message [] []
[2018-03-13 12:16:10] test.EMERGENCY: Test message [] []
[2018-03-13 12:16:10] test.ALERT: Test message [] []
[2018-03-13 12:16:10] test.ERROR: Test message [] []
[2018-03-13 12:16:10] test.NOTICE: Test message [] []
```

#### Výstup
```
error: 2
warning: 2
notice: 2
info: 1
emergency: 1
alert: 1
```

#### Implementace ve "starém" PHP
viz old.php
