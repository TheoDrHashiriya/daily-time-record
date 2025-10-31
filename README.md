# Daily Time Record
A simple time recording system with CRUD functionality, built on the MVC design.
## Main Features
- Role-based access control
- Multi-punch recording
- Responsive dashboard layout
- Dark Mode with smooth transitioning (TBI)
## Technical Stuff
- MVC design pattern
- PHP routing and the use of .htaccess for SEO-friendly URLs
- Passwords are hashed when stored in the database for security
- Fully normalized, 5NF-compliant database design
### CRUD Access Overview
| Role     |                 Create                 | Read                              | Update                            | Delete                            |
| -------- | :------------------------------------: | --------------------------------- | --------------------------------- | --------------------------------- |
| Admin    | Register Users (of all roles), Records | Own profile, Any user, Any record | Own profile, Any user, Any record | Own profile, Any user, Any record |
| Manager  |              Own records               | Any user, Any record              | Any user, Any record              | None                              |
| Employee |              Own records               | Any record                        | None                              | None                              |
### Simple Entity-Relationship Diagram
```mermaid
erDiagram
user{
int id PK
string first_name
string last_name
string middle_name
string username UK
string password
int created_by FK "references user(id)"
int role FK "references user_role(id)"
}

user_role{
int id PK
string name UK
}

event_record{
int id PK
int user_id FK
date record_date
datetime time
int type FK "references event_record_type(id)"
}

event_record_type{
int id PK
string name UK
}

user||--o{user:"creates"
user_role||--o{user:"has"

user||--o{event_record:"logs"
event_record_type||--o{event_record:"has"
```