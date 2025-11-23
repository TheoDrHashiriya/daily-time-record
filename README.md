# Daily Time Record
A simple time recording system with CRUD functionality, built on the MVCS design.
## Main Features
- Role-based access control
- Responsive dashboard layout
- Dark Mode with smooth transitioning (TBI)
## Technical Stuff
- MVCS (Models, Views, Controllers, Services) design pattern
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
---
config:
  layout: elk
---
erDiagram
department{
int id PK
string department_name
datetime created_at
}
user_role{
int id PK
string role_name UK
}
user{
int id PK
string first_name
string last_name
string middle_name
string username UK
string hashed_password
datetime created_at
int created_by FK "references user(id)"
int user_role FK "references user_role(id)"
int department FK "references department(id)"
}
notification{
int id PK
string title
string content
boolean has_been_read
datetime created_at
}
event_record_type{
int id PK
string type_name UK
}
event_record{
int id PK
int user_id FK
datetime event_time
int event_type FK "references event_record_type(id)"
}
user||--o{user:"creates"
user_role||--o{user:"has"
user}|--||department:"is in"
user}|--o|notification:"creates"
user||--o{event_record:"logs"
event_record_type||--o{event_record:"has"
```
### MVCS Flow
```mermaid
---
config:
  layout: dagre
---
flowchart TB
    db["Database"] -- data --> mod["Model"]
    serv["Service"] -- business logic --> con["Controller"]
    mod -- data access --> serv
    mod -- data for rendering --> con
    view["View"] -- user form data --> con
    con -- render data --> view
    mod -- handle --> db
    con -- request --> mod
```