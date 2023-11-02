Технические задания

Предложить оптимальное по скорости решение для выбора наиболее подходящей сети для IP-адресов как IPv4 так и IPv6. a. Создать структуру таблицы/таблиц MySQL для хранения данных по IP-сетям - сеть, маска, код страны. b. Создать SQL-запрос(ы) для выборки сети с наименьшей маской для заданного IP-адреса (IPv4 и IPv6).

Написать на Go и PHP сервисы для вывода данных по стране принадлежности.


Структура таблиц :


Таблица сетей 
```csharp 

CREATE TABLE `networks` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `network_address` blob NOT NULL,
  `netmask` int unsigned NOT NULL,
  `country_code_id` int unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1273 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
```

Таблица стран 
```csharp 
CREATE TABLE `countries` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `geoname_id` bigint NOT NULL,
  `continent_code` varchar(255) NOT NULL,
  `continent_name` varchar(255) NOT NULL,
  `country_iso_code` varchar(255) NOT NULL,
  `country_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=253 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
```




запросы 

IPv4 :

```csharp
SELECT *
FROM your_table
WHERE network_address = INET_ATON(?) & (0xFFFFFFFF << (32 - netmask))
JOIN countries ON networks.country_code_id = countries.geoname_id
ORDER BY netmask DESC
LIMIT 1
```
IPv6 :

```csharp
SELECT *
FROM your_table
WHERE network_address = INET6_ATON(?) & (0xFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFF << (128 - netmask))
JOIN countries ON networks.country_code_id = countries.geoname_id
ORDER BY netmask DESC
LIMIT 1
```


Мой способ  будет более оптимальный для вычисления  выборки сети с наименьшей маской для заданного IP-адреса (IPv4 и IPv6).
он будет быстрее всех по причине того что это очень быстрая операция на уровне машины и она может быть оптимизированна более эффективно чем вычисление математических промежутков 
+
Использование побитовых операций для манипуляции IP-адресами и масками подсетей является стандартной и эффективной практикой, особенно в контексте баз данных и сетевых запросов.

был выбран вариант с Побитовым сдвигом влево 
32 бита IPv4 
128 бит IPv6

позволяет создать маску кторая будет совпадать с сетевой частью ip адресса 

