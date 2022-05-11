using Microsoft.AspNetCore.Mvc;
using MySql.Data.MySqlClient;
using System.Data;
using ToDoApi.Models;

namespace notatnikApi.Controllers
{
    [ApiController]
    [Route("[controller]")]
    public class UsersController : Controller
    {
        string connectionString = "server=mysql4.webio.pl;database=22041_notatnik;uid=22041_admin;password=Z0h7N9klZa1[P];";

        [HttpGet]
        public UserData GetUserData(string email)
        {
            UserData userData = new UserData();

            MySqlConnection connection = new MySqlConnection(connectionString);

            try
            {
                connection.Open();

                Console.WriteLine("connection open");
                MySqlCommand cmd = new MySqlCommand("SELECT * FROM users WHERE email=@user;", connection);
                cmd.Parameters.AddWithValue("@user", email);
                cmd.CommandType = CommandType.Text;
                MySqlDataReader reader = cmd.ExecuteReader();

                if (reader.Read())
                {
                    userData.id = reader.GetInt32(0);
                    userData.user = reader.GetString(1);
                    userData.pass = reader.GetString(2);
                    userData.email = email;
                }


            }
            catch (Exception ex)
            {
                Console.WriteLine(ex.Message);
            }
            finally
            {
                if (connection.State == System.Data.ConnectionState.Open)
                {
                    connection.Close();
                }
            }
            return userData;
        }


        [HttpPost]
        public void NewUser([FromBody] UserData userData)
        {

            MySqlConnection connection = new MySqlConnection(connectionString);

            try
            {
                connection.Open();

                Console.WriteLine("connection open");
                MySqlCommand cmd = new MySqlCommand("INSERT INTO users(user,pass,email)Values(@user,@pass,@email);", connection);
                cmd.Parameters.AddWithValue("@user", userData.user);
                cmd.Parameters.AddWithValue("@pass", userData.pass);
                cmd.Parameters.AddWithValue("@email", userData.email);
                cmd.CommandType = CommandType.Text;
                MySqlDataReader reader = cmd.ExecuteReader();
            }
            catch (Exception ex)
            {
                Console.WriteLine(ex.Message);
            }
            finally
            {
                if (connection.State == System.Data.ConnectionState.Open)
                {
                    connection.Close();
                }
            }
        }




    }
}
