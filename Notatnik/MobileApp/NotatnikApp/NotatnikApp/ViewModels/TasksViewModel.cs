using NotatnikApp.Models;
using NotatnikApp.Views;
using System;
using System.Collections.Generic;
using System.Collections.ObjectModel;
using System.Net.Http;
using System.Net.Http.Headers;
using System.Text;
using System.Threading.Tasks;
using Xamarin.Essentials;
using Xamarin.Forms;

namespace NotatnikApp.ViewModels
{
    internal class TasksViewModel : BaseViewModel
    {
        private static TasksViewModel _it;
        public static TasksViewModel It
        {
            get
            {
                if (_it == null)
                    _it = new TasksViewModel();
                return _it;
            }
        }



        private int listId;

        public int ListId
        {
            get
            {
                return listId;
            }
            set
            {
                listId = value;
                OnPropertyChanged();
                LoadTasks(value);
            }
        }

        private string name;
        public string Name
        {
            get
            {
                return name;
            }
            set
            {
                name = value;
            }
        }


        private ObservableCollection<TaskData> _tasks { get; set; }
        public ObservableCollection<TaskData> Tasks
        {
            get { return _tasks; }
            set
            {
                _tasks = value;
                OnPropertyChanged();
            }
        }

        public Command Add { get; }
        public Command Post { get; }

        public TasksViewModel()
        {
            Tasks = new ObservableCollection<TaskData>();
            Add = new Command(AddTask);
            Post = new Command(async () => await PostTask());
            LoadTasks(ListId);
        }

        public async void DoneChanged(int id, bool isDone)
        {
            client.DefaultRequestHeaders.Accept.Clear();
            string url;

            if (isDone)
            {
                url = $"TaskDone";
                await client.PostAsJsonAsync(url, id);
            }
            else
            {
                url = $"TaskDone?id={id}";

                await client.GetAsync(url);
            }

        }


        public async void LoadTasks(int id)
        {
            client.DefaultRequestHeaders.Accept.Clear();
            client.DefaultRequestHeaders.Accept.Add(new MediaTypeWithQualityHeaderValue("application/json"));

            string url = $"Tasks?listId={ id }";

            HttpResponseMessage response = await client.GetAsync(url);
            if (response.IsSuccessStatusCode)
            {
                Tasks.Clear();
                ObservableCollection<TaskData> taskData = await response.Content.ReadAsAsync<ObservableCollection<TaskData>>();
                foreach (TaskData current in taskData)
                {
                    Tasks.Add(current);
                }
            }
            else
            {
                throw new Exception(response.ReasonPhrase);
            }
        }


        public async Task PostTask()
        {
            client.DefaultRequestHeaders.Accept.Clear();
            string url = $"Tasks";

            TaskData task = new TaskData()
            {
                id = 0,
                name = Name,
                ListId = ListId,
                done = false
            };

            await client.PostAsJsonAsync(url, task);


            await Xamarin.Forms.Application.Current.MainPage.Navigation.PopAsync();
            LoadTasks(ListId);
        }

        public async void DeleteTask(int id)
        {
            client.DefaultRequestHeaders.Accept.Clear();

            string url = $"TaskDelete?id={ id }";

            await client.GetAsync(url);

            LoadTasks(ListId);
        }

        private void AddTask()
        {
            Xamarin.Forms.Application.Current.MainPage.Navigation.PushAsync(new NewTaskPage());
        }
    }
}
