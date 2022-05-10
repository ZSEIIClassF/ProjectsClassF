using Microsoft.AspNetCore.Mvc;
using MySql.Data.MySqlClient;
using System.Data;

namespace notatnikApi.Controllers
{

    [ApiController]
    [Route("[controller]")]
    public class TaskDoneController : Controller
    {
        string connectionString = "server=mysql4.webio.pl;database=22041_notatnik;uid=22041_admin;password=Z0h7N9klZa1[P];";

        [HttpGet]
        public void TaskUnDone(int id)
        {

            MySqlConnection connection = new MySqlConnection(connectionString);

            try
            {
                connection.Open();

                Console.WriteLine("connection open");
                MySqlCommand cmd = new MySqlCommand("UPDATE tasks SET done=false WHERE id=@id;", connection);
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


        [HttpPost]
        public void TaskDone([FromBody] int id)
        {

            MySqlConnection connection = new MySqlConnection(connectionString);

            try
            {
                connection.Open();

                Console.WriteLine("connection open");
                MySqlCommand cmd = new MySqlCommand("UPDATE tasks SET done=true WHERE id=@id;", connection);
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
