# MyFoodHub

## Table of Contents
1. [Abstract](#abstract)
2. [Introduction](#introduction)
3. [Tools and Technologies](#tools-and-technologies)
4. [Database Design](#database-design)
5. [Entity-Relationship Diagram](#entity-relationship-diagram)
6. [Implementation](#implementation)
7. [Challenges and Solutions](#challenges-and-solutions)
8. [Results](#results)
9. [Conclusion](#conclusion)

---

## Abstract
**MyFoodHub** is a web-based platform designed to facilitate seamless food ordering by connecting customers with a wide range of restaurants. The project centers on developing and implementing a robust relational database using MySQL, aimed at managing users, restaurants, menu items, orders, and order details. This documentation outlines the database schema, implementation process, encountered challenges, and final outcomes.

---

## Introduction
In today's digital era, online food ordering systems have become indispensable for restaurants and customers alike. **MyFoodHub** addresses this need by offering a streamlined and intuitive platform for food ordering. At the core of this system lies a well-structured database that ensures efficient data management and smooth interactions between system components.

---

## Tools and Technologies
- **Database Management**: MySQL, phpMyAdmin
- **Backend Development**: PHP
- **Frontend Development**: HTML5, CSS3
- **Development Environment**: Visual Studio Code

---

## Database Design
The database consists of five primary tables, designed to ensure data integrity and efficiency:

1. **Users**: Stores customer and restaurant owner details.
2. **Restaurants**: Maintains information about restaurants, including ownership and cuisine types.
3. **MenuItems**: Holds details about menu items offered by restaurants.
4. **Orders**: Tracks orders placed by customers.
5. **Order_Items**: Represents individual items within each order.

---

## Entity-Relationship Diagram
The database design follows standard relational modeling principles. The key relationships between entities are as follows:

1. **Users → Orders (Places)**  
   - A user can place multiple orders, but each order is associated with a single user.  
   - **Cardinality**: 1:N.

2. **Users → Restaurants (Owns)**  
   - A restaurant owner can own multiple restaurants, but each restaurant belongs to one owner.  
   - **Cardinality**: 1:N.

3. **Restaurants → MenuItems (Has)**  
   - A restaurant can offer multiple menu items, and each menu item is tied to a single restaurant.  
   - **Cardinality**: 1:N.

4. **Orders → Order_Items (Includes)**  
   - An order can include multiple items, with each item belonging to a specific order.  
   - **Cardinality**: 1:N.

5. **MenuItems → Order_Items (Contains)**  
   - A menu item can appear in multiple order items, but each order item references one specific menu item.  
   - **Cardinality**: 1:N.

---

## Implementation

### Setting Up the Database
1. **Table Creation**:  
   - Designed and implemented tables for `users`, `restaurants`, `menu_items`, `orders`, and `order_items` using MySQL.
   - Defined appropriate primary and foreign keys to maintain data integrity.

2. **Establishing Relationships**:  
   - Created foreign key constraints to link related tables:
     - `users ↔ restaurants`: A restaurant is owned by a user.
     - `restaurants ↔ MenuItems`: Menu items are associated with specific restaurants.
     - `users ↔ orders`: Connects users to their orders.
     - `orders ↔ order_items`: Each order contains multiple items.

3. **Data Insertion**:  
   - Populated tables with sample data, including users, restaurants, and menu items.

### Backend Integration
- Developed PHP scripts to support:
  - User registration and authentication.
  - Restaurant and menu item management.
  - Secure query execution using prepared statements to prevent SQL injection.

### Frontend Development
- Designed a clean and responsive interface using HTML5 and CSS3.
- Incorporated sections to dynamically display restaurants and menu items.

---

## Challenges and Solutions

1. **Foreign Key Constraint Errors**  
   - **Issue**: Errors occurred when inserting records with non-existent foreign key references.  
   - **Solution**: Ensured referenced records existed before performing insert operations.

2. **Image Loading Issues**  
   - **Issue**: Images were not displaying properly.  
   - **Solution**: Verified image paths and ensured proper naming conventions.

3. **Responsive Design Adjustments**  
   - **Issue**: Ensuring the website displayed correctly across devices.  
   - **Solution**: Utilized CSS media queries to optimize the layout for various screen sizes.

---

## Results
- Successfully implemented a relational database with strong data integrity.
- Achieved seamless integration between the backend and frontend.
- Delivered a user-friendly, responsive interface for customers and restaurant owners.
- Demonstrated the feasibility of the system through functional features such as order placement and restaurant management.

---

## Conclusion
The **MyFoodHub** project showcases the application of database design principles in building a secure and functional food ordering system. By methodically addressing challenges and implementing scalable solutions, the project delivers an intuitive platform that meets the needs of both customers and restaurant owners. This work highlights the importance of robust database management and effective backend integration in modern web applications.
