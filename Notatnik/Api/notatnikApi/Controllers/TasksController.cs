using Microsoft.AspNetCore.Mvc;
using MySql.Data.MySqlClient;
using System.Data;
using ToDoApi.Models;

namespace notatnikApi.Controllers
{
    [ApiController]
    [Route("[controller]")]
    public class TasksController : Controller
    {

        string connectionString = "server=mysql4.webio.pl;database=22041_notatnik;uid=22041_admin;password=Z0h7N9klZa1[P];";


        [HttpGet]
        public List<TaskData> GetListTasks(int listId)
        {
            List<TaskData> tasks = new List<TaskData>();
            MySqlConnection connection = new MySqlConnection(connectionString);

            try
            {
                connection.Open();

                Console.WriteLine("connection open");
                MySqlCommand cmd = new MySqlCommand("SELECT * FROM tasks WHERE listId=@listId;", connection);
                cmd.Parameters.AddWithValue("@listId", listId);
                cmd.CommandType = CommandType.Text;
                MySqlDataReader reader = cmd.ExecuteReader();

                while (reader.HasRows)
                {
                    while (reader.Read())
                    {
                        tasks.Add(new TaskData
                        {
                            id = reader.GetInt32(0),
                            name = reader.GetString(1),
                            listId = reader.GetInt32(2),
                            done = reader.GetBoolean(3)
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
            return tasks;
        }


        [HttpPost]
        public void NewTask([FromBody] TaskData taskData)
        {

            MySqlConnection connection = new MySqlConnection(connectionString);

            try
            {
                connection.Open();

                Console.WriteLine("connection open");
                MySqlCommand cmd = new MySqlCommand("INSERT INTO tasks(name,listId, done)Values(@name, @listId, false);", connection);
                cmd.Parameters.AddWithValue("@name", taskData.name);
                cmd.Parameters.AddWithValue("@listId", taskData.listId);
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
        public void DeleteTask(int id)
        {

            MySqlConnection connection = new MySqlConnection(connectionString);

            try
            {
                connection.Open();

                Console.WriteLine("connection open");
                MySqlCommand cmd = new MySqlCommand("DELETE FROM tasks WHERE id=@id;", connection);
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
