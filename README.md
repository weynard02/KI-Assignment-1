
| NRP        | Name                          |
|------------|-------------------------------|
| 5025211008 | Muhammad Razan Athallah       |
| 5025211014 | Alexander Weynard Samsico     |
| 5025211139 | Apta Rasendriya Wijaya        |
| 5025211158 | Ghifari Maaliki Syafa Syuhada |


# Report
To showcase the difference between 3 distinct encryption method, namely Advanced Encryption Standard (AES), Data Encryption Standard (DES), and RC4, we made a simple CRUD with the following features:
1. Authenticating user with password
2. Giving file access to authorized user
3. Encrypting files and storing it in the database
4. Decrypting stored files before downloading

## Benchmark
The method that we are using to benchmark the 3 encryption method is by comparing the decryption time for each method for 3 different file sizes 500kB, 1MB, and 2 MB. The reason the file size is small is due to the size restriction that is given for an HTTP POST request. The results are as follows:

### AES
![1](images/AES.png)
