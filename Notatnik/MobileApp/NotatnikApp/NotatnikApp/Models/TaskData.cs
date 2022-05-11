using System;
using System.Collections.Generic;
using System.Text;

namespace NotatnikApp.Models
{
    public class TaskData
    {
        public int id { get; set; }
        public string name { get; set; }
        public int ListId { get; set; }
        public bool done { get; set; }
    }
}
