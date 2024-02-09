# IZI PARKIN
The IZI PARKING Management System is a database project designed to efficiently manage and track the operations of a car parking lot. The system aims to automate and streamline the process of vehicle parking, entry/exit management, customer registration, and reporting.

## Features
- Customer Registration and Login Page
- Nearby and Recommended Parking Lots for Customer
- Parking Lots Searching System
- Editable Customer Profile
- Parking Lots Review and Rating
- Staff Login Page
- Staff Can Change Reservation Status of Customer

## Tools Used
- HTML
- PHP
- JavaScript
- CSS

## Entity Relationship Diagram (ERD)
!ERD

## Permission Table
| User Role | Table Column(s) | Permission |
| --- | --- | --- |
| Customer | car: car_id, car_brand | SELECT |
| | car_license: car_id, license_plate, plate_region | SELECT, INSERT |
| | customer_tb: customer_id, name, email, phone_number, username, password, car_id | SELECT, INSERT, UPDATE, DELETE |
| | parking_lot_tb: parking_lot_id, capacity, price_per_hour, price_per_day, location | SELECT |
| | reservation_tb: reservation_id, customer_id, parking_lot_id, start_time, end_time, reservation_status | SELECT, INSERT, UPDATE, DELETE |
| | payment_method_type_tb: type_id, name | SELECT |
| | payment_method_tb: payment_id, customer_id, payment_type_id, card_number, expiration_date, security_code, billing_address | SELECT, INSERT, UPDATE, DELETE |
| | payment_tb: reservation_id, payment_id, payment_type_id, payment_amount, payment_time | SELECT, INSERT |
| | rating_tb: rating_id, customer_id, parking_lot_id, rating_date, cleanliness_rating, security_rating, convenience_rating, price_rating, overall_rating | SELECT, INSERT |
| | review_tb: review_id, rating_id, review_text, review_photo, review_video | SELECT, INSERT |
| | tiers: tier_id, tier_name, points_threshold, points_per_parking, points_per_rating, points_per_review | SELECT |
| | customer_loyalty_tb: customer_id, loyalty_program_id, points_balance, tier_id | SELECT, INSERT, UPDATE |
| | notification_tb: notification_id, customer_id, notification_type, notification_message, notification_time | SELECT, INSERT |
| Parking Lot Owner | parking_lot_owner_tb: parking_lot_owner_id, name, email, phone_number | SELECT |
| | parking_lot_tb: parking_lot_id, parking_lot_owner_id, capacity, price_per_hour, price_per_day, location | SELECT, INSERT, UPDATE, DELETE |
| | reservation_tb: reservation_id, customer_id, parking_lot_id, start_time, end_time, reservation_status | SELECT |
| Staff | staff_tb: staff_id, staff_name, staff_email, staff_password, staff_phone, staff_address, staff_role | SELECT, INSERT, UPDATE, DELETE |

## User Views and Entities
| User Role | Entities |
| --- | --- |
| Customer | car, car_license, customer_tb, parking_lot_tb, reservation_tb, payment_method_type_tb, payment_method_tb, payment_tb, rating_tb, review_tb, tiers, customer_loyalty_tb, notification_tb |
| Parking Lot Owner | parking_lot_owner_tb, parking_lot_tb, reservation_tb |
| Staff | staff_tb, support_tb, customer_tb, reservation_tb |

### User Roles
- **Customer**: Customers are individuals who use the parking lot reservation system to reserve parking spots. They can create an account, search for available parking lots, make reservations, and provide ratings and reviews of parking lots. Customers can also manage their payment information and receive notifications regarding their reservations.
- **Parking Lot Owner**: Parking lot owners are individuals who own and manage parking lots. They can view and manage their parking lot information such as capacity, location, and pricing. Parking lot owners can also view reservation details for their parking lots.
- **Staff**: Staff members are employees who work for the parking lot reservation system. They have access to customer support and can view support messages from customers. Staff members can also manage customer accounts and reservations.

## CPE 231 Database Systems (2/65)
### Car Parking System Midterm Project Report
#### Submitted by
- Mr. Nathkeam Niyamasindhu 64070503416
- Mr. Nutt Ratanakul 64070503417
- Mr. Pongpanod Puavilai 64070503435
- Mr. Tantham Kanjanangkulpan 64070503464
- Mr. Pacharawat Asawasuntikul 64070503467

#### Submitted to
- Dr. Phond Phunchongharn
- Dr. Khajonpong Akkarajitsakul

#### Institution
King Mongkut's University of Technology Thonburi (KMUTT)
