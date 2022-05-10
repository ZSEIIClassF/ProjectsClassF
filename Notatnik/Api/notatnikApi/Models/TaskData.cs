namespace ToDoApi.Models
{
    public class TaskData
    {
        public int id { get; set; }
        public string name { get; set; }
        public int listId { get; set; }
        public bool done { get; set; }
    }
}
