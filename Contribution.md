Contribution
1. Add modifications to the project
   To propose modifications to the application, you should install Git on your computer and have a github account.

A.Configure Git
Don't forget to configure Git :

Indicate your identity :
"git config --global user.name 'John Doe'"
"git config --global user.email 'john.doe@example.com'"

B. Install the project on your computer
See README.md

C. Commit your work with Git
Commit your modifications allow you to keep trace of the modifications and to go back in case of error.

Run Git on your computer
Place yourself in the project repository (use "cd" command)
Control the existant branches in the project : "git branch"
If the feature branch on which you want to work allready exists : "git checkout feature-branch-name"
If you should create a branch for a new feature:
Place yourself on the branch "dev" : "git checkout dev"
Create a branch for your feature and place yourself on it by typing : "git checkout -b new-feature-branch-name" (usually, branch name is formated like this : "feature/purpose_of_the_feature")
After modifications, add all the files you modified to stage by typing "git add ."
Create a commit to register your modifications to these files by typing "git commit -m "comment"", then write a comment in the editor to explain what you did, on the first line.
D. Push your code on Github
The first time you want to push something on the remote repository, place yourself in the project repository with "Git" (use "cd" command) and create a link between your computer and the remote repository by typing : "git remote add origin https://github.com/d4rkstrife/P8_ToDoCo.git" ("origin" is the conventional name for a remote repository but you can named it as you wish. Just make sure to remember it.)

The first time you want to push a branch on the remote repository, you should create a branch on the remote repository where to send your feature branch by typing : "git push --set-upstream origin branch-name-on-remote-repository" (Usually the name on the remote repository is the same as the one on the local repository)

The next times you should simply type : "git push" to push your code on "Github"

E. Make a pull request
When your feature is complete and you push your last commits on the remote repository, you should make a pull request to ask your peer to review your code before merge it in develop branch.

On github:

go to the project page
Click on Pull request tab
click on the "New pull request" green button on the right.
choose :
the branch where you want to merge yours (main)
the branch to merge in (your branch)
Then click on "Create pull request" green button on the right
Leave a message to your peers ans click on the publish button.
wait for codacy analysis results. If something wrong correct your code and push it again (see below for further informations)
when the branch is validated (you'll see a green check icon next to the reviewer name in the right panels named "Reviewers"), click on "Merge pull request" green button at the bottom of the page.
click on "Delete branch" button
F. Pull modifications into your local repository
Before start to work, you should retrieve project modifications :

on "main" branch
on the branch you work on.
To do so, in Git, you should for these two branches :

Place yourself on the branch by typing : "git checkout branch-name"

control than your local branch is uptodate with remote repository by typing : "git log" and check if the HEAD and the remote branch are on the same commit. If the remote branch have some additional commits, you should :

Control branch compatibility by typing "git fetch"
Then, download branch modifications by typing "git pull"
If the branch you just pull from is "main" :

if the commit pulled is a merge from an other branch (see "git log" results), don't forget to delete the branch on your local repository : "git branch -d merged-branch-into-develop-name"
If you are allready working on an other branch, don't forget to rebase. For that:
place yourself on your working branch : "git checkout working-branch-name"
Then typing : "git rebase main"
Resolve conflicts :
when a conflict shows up, type : "git mergetool"
in the center choose the code you want to save
Save and close the mergetool
Control the code and if all is alright delete "file-modified.ext.orig"
control if some modifications should be committed : "git status"
if so, staged it : "git add ." then commit "git commit" with a comment.
Then, pursue rebase by typing "git rebase --continue"
And so on until rebase end.
Push the branch uptodate on remote repository with : "git push --force-with-lease"
2. Quality process
   To guarantee the application quality, you should :

A. Before development
Install tools for code quality in your IDE
like "php-cs-fixer". These extensions will check your code to ensure code quality.

Know the PSRs These tools recommanded helps to respect standards but you need to know about them too :

PSR-1 : Basic Coding Standard
PSR-4 : Autoloading Standard
PSR-12 : Extended Coding Style Guide
B. During development
Perform already writen tests for existant features. Some tests for existant features already exists in the application. When you modify something in the code, you should allways perform all unit and functional tests existant to be sure the application still work. If you don't know how, please, see the tests documentation.

Write tests for new features When you create a new feature, you should write new tests to check if your code is functionnal. To write :

unit tests, please read PHPUnit documentation

