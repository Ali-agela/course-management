##  Overview of the system  "course - management - system " 
 
 the system is designed to serve the simple functionality of any course management  systems 
 functions   like creating accounts, managing courses, enrolling, and even leaving comments 
 the purpose of the system was to show my skills as one of the candidates to a back-end developer in Artisans and not based on any real system ideas 





## Features  of the system
 as said earlier the system provides a very simple Features and they will be discussed below :
    
    # regestration
     the first Features provided by the system is the ability to create accounts 
     any user in the system can create an account for himself by providing his name, email, password, and role 
     as is for the role he can assign any role to himself except the role of  admin the role of admin can be only created by another admin
     any wrong input or duplicated emails will be rejected by the system


    # authentican and authorization
    
     one of the important  Features provided by the system is the ability to check on the user to make sure he is who he claims to be (authentication)
     and knowing what he can do (authorization) the system does this by the use of the login API which the user uses by providing an email and a   password then if the credentials are correct he will get a token that will serve for the rest of the operations 

    #course management 

     the system allows any user with an instructor role to create and manage a course he can create a course by providing some data like the name of the course a short description also the price duration and category no other instructor nor student can modify or delete an instructors course but only an  admin can create a course for an instructor modify it and even delete it another Feature in the course management is the ability to add lessons in a course an instructor can add a list of lessons in his course and even upload a video for each lesson and any student enrolled in the course can access and see the lessons 


    # Review and Ratings 
     another Feature we have is that a student can leave his comments  and a rating on any course the reviews can be seen by all users but can only be modified and deleted  by the student who wrote them, never the less an admin has also the power to delete any review if he feels like it 

    # end point filtring 
     the system provides a way to filter courses in a single request depending on the data sent if there was no data sent for all the courses returned  you could add the price key so u can only show courses under that price or use the category key so you can get courses in the same topics The The 
     same Feature is provided for the users but it can only be used by admins and it uses the same concept no data you get all users  and the users get filtered depending on the keys you add 

## Requirements
    so you can be able to run the project you will need the following stacks :
       PHP 8.2 or higher
       Composer (for dependency management)
       Laravel 10 or higher 
       for the database, I used SQLite while development but when deployed I switched to PostgreSQL since it was the one provided by the host
        sanctum   for (authentication )



## Installation
  if you would like to try  installing the system yourself  follow these steps 

    1) run this command in your CLI :   git clone https://github.com/Ali-agela/course-management.git  
        this will get the project to your local machine 
    2)when you open the  project folder you will find in the root directory a file named .env.example rename this file to   .env 
    3) run this command in your CLI : php artisan key:generate
        this command will generate the app_key for you   
        now this environment uses sqlite as the database and your machine as a host if you wish to change the database you will find the database settings in the same file 
    4)run php artisan migrate:fresh this will create all the tables in the database 
    5)run php artisan db:seed   this will fill the database with some data for testing 
    6) run php artisan test   if all the tests passed then you are good to go if not try to debug the problem
    7)then run php artisan serve 

### Usages and API documentaion 
    to see any example usages of the API's and to see the full documentation follow those links 
    https://lati55.postman.co/workspace/Travel~9c199bcd-3716-4648-9984-4e1ee1a20ec0/documentation/36422747-46a1d820-d73c-4379-9272-02a3ffa2981e  for the documentation 
    https://lati55.postman.co/workspace/Travel~9c199bcd-3716-4648-9984-4e1ee1a20ec0/collection/36422747-46a1d820-d73c-4379-9272-02a3ffa2981e?action=share&creator=36422747  
    and this is for the post-man collection 

    ## Notice that the project is already hosted and live the postman collection is made to test the deployment version  
    if you want Postman to send the request to the one in your local machine change the BASE_URL variable in the collection to your localhost

#### the project is hosted under the URL   https://course-management-test.onrender.com/
