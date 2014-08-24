sn1
===

Social networking

First run:
http://localhost/sn1/run.php/read

Direct friends: Return those people who are directly connected to the chosen person.
friends api: 
http://localhost/sn1/run.php/friends/1 [Here 1 is the user id]

Friends of friends: Return those who are two steps away, but not directly connected to the chosen person.
friends of friends api:
http://localhost/sn1/run.php/friends_of_friends/1  [Here 1 is the user id]

Suggested friends: Return people in the group who know 2 or more direct friends of the chosen person, but are not directly connected to her.
suggested friends api:
http://localhost/sn1/run.php/suggested_friends/1 [Here 1 is the user id]
