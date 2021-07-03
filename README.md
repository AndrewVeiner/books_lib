# library of books

## deploy 
1. Склонировать и зайти в папку с проектом

``git clone https://github.com/AndrewVeiner/books_lib.git && cd books_lib``

2. Установка зависимостей

``composer install``

При возникновении ошибки разных версий php, можно попробывать

``composer install --ignore-platform-reqs``

3. Применить миграцию

``bin/console doctrine:migrations:migrate``

4. Создать админа для админки

``bin/console fos:user:create --super-admin admin 'admin@admin.ru' admin
``

5. Запустить!

``bin/console server:start``

6. ENJOY


## SQL-запрос

``select * from books, authors_books where books.id = authors_books.books_id GROUP BY authors_books.authors_id
HAVING count(authors_books.authors_id) > 2``