using Microsoft.AspNetCore.Mvc;
using MySql.Data.MySqlClient;
using System.Data;
using ToDoApi.Models;

namespace notatnikApi.Controllers
{
    [ApiController]
    [Route("[controller]")]
    public class ListsController : Controller
    {

        string connectionString = "server=mysql4.webio.pl;database=22041_notatnik;uid=22041_admin;password=Z0h7N9klZa1[P];";

        [HttpGet]
        public List<ListData> GetUserLists(int userId)
        {
            List<ListData> lists = new List<ListData>();
            MySqlConnection connection = new MySqlConnection(connectionString);

            try
            {
                connection.Open();

                Console.WriteLine("connection open");
                MySqlCommand cmd = new MySqlCommand("SELECT * FROM lists WHERE userId=@userId;", connection);
                cmd.Parameters.AddWithValue("@userId", userId);
                cmd.CommandType = CommandType.Text;
                MySqlDataReader reader = cmd.ExecuteReader();

                while (reader.HasRows)
                {
                    while (reader.Read())
                    {
                        lists.Add(new ListData
                        {
                            id = reader.GetInt32(0),
                            name = reader.GetString(1),
                            userId = reader.GetInt32(2)
                        });
                    }
                    reader.NextResult();
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
            return lists;
        }


        [HttpPost]
        public void NewList([FromBody] ListData listData)
        {

            MySqlConnection connection = new MySqlConnection(connectionString);

            try
            {
                connection.Open();

                Console.WriteLine("connection open");
                MySqlCommand cmd = new MySqlCommand("INSERT INTO lists(name,userId)Values(@name,@userId);", connection);
                cmd.Parameters.AddWithValue("@name", listData.name);
                cmd.Parameters.AddWithValue("@userId", listData.userId);
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

        [HttpDelete]
        public void DeleteList(int id)
        {

            MySqlConnection connection = new MySqlConnection(connectionString);

            try
            {
                connection.Open();

                Console.WriteLine("connection open");
                MySqlCommand cmd = new MySqlCommand("DELETE FROM lists WHERE id=@id;", connection);
                cmd.Parameters.AddWithValue("@id", id);
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
