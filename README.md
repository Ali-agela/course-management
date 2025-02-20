##  Overview of the system  "course - management - system " 
 
 the system is designed to serve the simple functionality of any course management  systmes 
 functions   like creating accounts, managing courses, enrrolling and even leaving comments 
 the porpose of the system was to show my skills as one of the canidiates to a back-end developer in artisans and not based on any real system ideas 





## Features  of the system
 as said earliar the system provides a very simple Features and they will be discussed below :
    
    # regestration
     the first Features provided by the system is the ability to create accounts 
     any user in the system can create an account for him self by providing his name,email,password, and role 
     as is for the role he can assign any role to him self except the role of  admin the role of admin can be only created by another admin
     any wrong input or dublicapted emails will be rejected by the system


    # authentican and autharizationn
    
     one of the importan  Features provided by the system is the ability to check on the user make sure he is who he claims to be (authentication)
     and knowing what can he do (authrizaion) the system does this by the use of the login api which the user uses by providing a email and a   passwprd which then if the cridintials are correct he will get a token that will serve for the rest of the opreations 

    #course management 

     the system allows any user with a an instructor role to create and manage a course he can create a course by providing some data like the name of the course a short description also the price and duration and category no other instrucor nor student can modify or delete an instructors course but only an  admin can an admin can create a course for an instructor modify it and even delete it another Feature in the course management is the ability to add lessons in a course a instructor can add a list of lessons in his course and even upload a video for each lesson and any student enrolled in the course can acces and see the lessons 


    # Review and Ratings 
     another Feature we have is that a student can leave his comments  and a rating on any course the reviews can be seen by all users but can only be modified and deleted  by the student who wrote them, never the less an admin has also the power to delete any review if he feels like it 

    # end point filtring 
     the system provides a way to filter courses in a single request depends on the data sent if there was no data sent all the courses return  you could add the price key so u can only show courses under that price or use the category key so you can get courses in the same topics 
     same Feature is provided for the users but it can only be used by admins and it uses the same consept no data you get all users  and the users get filtterd depnds on the keys you add 

## Requirements
    so you can be able to run the project you will need the follwing stacks :
       PHP 8.2 or higher
       Composer (for dependency management)
       Laravel 10 or higher 
       for the database I used sqlite while development but when deployed i switched to PostgreSQL since it was the one provided by the host



## Installation
  if you would like to try  installing the sytem your self follow this steps 

    1) run this command in your cli  :   git clone https://github.com/Ali-agela/course-management.git  
        this will get the project in your local machine 
    2)when you open the  project folder you will find in the root dirctory a file named .env.example rename this file to   .env 
    3) run this commnd in your cli : php artisan key:generate
        this command will generat the app_key for you   
        now this enviroment uses sqlite as database and your machine as a host if you wish to change the database you will find the database settings in the same file 
    4)run php artisan migrate:fresh this well create all the tables in the databse 
    5)run php artisan db:seedd   this will fell the databse with some data for testing 
    6)then run php artisan serve 

### Usages and API documentaion 
    to see any example usages of the api`s and to see the full documention follow thoses links 
    https://lati55.postman.co/workspace/Travel~9c199bcd-3716-4648-9984-4e1ee1a20ec0/documentation/36422747-46a1d820-d73c-4379-9272-02a3ffa2981e  for the documention 
    https://lati55.postman.co/workspace/Travel~9c199bcd-3716-4648-9984-4e1ee1a20ec0/collection/36422747-46a1d820-d73c-4379-9272-02a3ffa2981e?action=share&creator=36422747  
    and this for the post man collection 

    ## notice that the project is already hosted and live the postman collection is made to test the deployment version  
    if you want postman to send the request to the one in your local machine change the BASE_URL varible in the collection to your localhost

#### the project is hosted under the URL   https://course-management-test.onrender.com/