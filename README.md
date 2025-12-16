# Daily Time Record

A daily time recording system with CRUD functionality, built on the MVCS design.

## Main Features

- QR code-based time-in/time-out
- Role-based access control
- Attendance metrics
- Responsive dashboard layout
- Dark Mode

## Technical Stuff

- **MVCS** (Models, Views, Controllers, Services) design pattern
- PHP routing and the use of Apache's web server config for SEO-friendly URLs
- Passwords are hashed when stored in the database for security
- Normalized, 3NF-compliant database design

### CRUD Access Overview

| Role     |                 Create                 | Read                              | Update                            | Delete                            |
| -------- | :------------------------------------: | --------------------------------- | --------------------------------- | --------------------------------- |
| Admin    | Register Users (of all roles), Records | Own profile, Any user, Any record | Own profile, Any user, Any record | Own profile, Any user, Any record |
| Manager  |              Own records               | Any user, Any record              | Any user, Any record              | None                              |
| Employee |              Own records               | Any record                        | None                              | None                              |

### Physical Data Model

```mermaid
---
config:
  layout: elk
---
erDiagram
 direction TB
 DEPARTMENT {
  int id PK ""  
  varchar department_name  ""  
  varchar abbreviation  ""  
  time standard_am_time_in  ""  
  time standard_am_time_out  ""  
  time standard_pm_time_in  ""  
  time standard_pm_time_out  ""  
  datetime created_at  ""  
 }

 USER_ROLE {
  int id PK ""  
  varchar role_name  ""  
 }

 USER {
  int id PK ""  
  varchar first_name  ""  
  varchar last_name  ""  
  varchar middle_name  ""  
  varchar username  ""  
  varchar email  ""  
  varchar hashed_password  ""  
  char user_number  ""  
  datetime created_at  ""  
  int created_by FK ""  
  int user_role FK ""  
  int department FK ""  
  datetime attendance_effective_date  ""  
 }

 EVENT_RECORD_TYPE {
  int id PK ""  
  varchar type_name  ""  
 }

 EVENT_RECORD {
  int id PK ""  
  datetime event_time  ""  
  int event_type FK ""  
  int user_id FK ""  
 }

 QR_CODE {
  int id PK ""  
  int user_id FK ""  
  varchar qr_string  ""  
  datetime created_on  ""  
  datetime expired_on  ""  
 }

 SYSTEM_LOG_TYPE {
  int id PK ""  
  varchar type_name  ""  
  boolean is_notification  ""  
 }

 SYSTEM_LOG {
  int id PK ""  
  int system_log_type FK ""  
  varchar title  ""  
  text content  ""  
  datetime created_at  ""  
  int created_by FK ""  
 }

 NOTIFICATION_READ {
  int id PK ""  
  int system_log FK ""  
  int user_id FK ""  
  datetime read_at  ""  
 }

 DEPARTMENT||--o{USER:"has"
 USER_ROLE||--o{USER:"assigned to"
 USER||--o{EVENT_RECORD:"creates"
 EVENT_RECORD_TYPE||--o{EVENT_RECORD:"categorizes"
 USER||--o{QR_CODE:"owns"
 SYSTEM_LOG_TYPE||--o{SYSTEM_LOG:"defines"
 USER||--o{SYSTEM_LOG:"creates"
 SYSTEM_LOG||--o{NOTIFICATION_READ:"has"
 USER||--o{NOTIFICATION_READ:"reads"
 USER||--o{USER:"created_by"
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
