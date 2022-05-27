using NotatnikApp.Models;
using NotatnikApp.Views;
using System;
using System.Collections.Generic;
using System.Collections.ObjectModel;
using System.Linq;
using System.Net.Http;
using System.Net.Http.Headers;
using System.Text;
using System.Threading.Tasks;
using ToDoApp.Views;
using Xamarin.Essentials;
using Xamarin.Forms;

namespace NotatnikApp.ViewModels
{
    internal class AppShellViewModel : BaseViewModel
    {
        //acces view model from 2 pages
        private static AppShellViewModel _it;
        public static AppShellViewModel It
        {
            get
            {
                if (_it == null)
                    _it = new AppShellViewModel();
                return _it;
            }
        }



        // List of user to do lists in data base
        private ObservableCollection<ListData> _lists { get; set; }
        public ObservableCollection<ListData> Lists
        {
            get { return _lists; }
            set
            {
                _lists = value;
                OnPropertyChanged();
            }
        }

        public Command<ListData> openList { get; }
        public Command newListPage { get; }
        public Command addList { get; }



        // new list page property
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


        private string username;
        public string Username
        {
            get
            {
                return username;
            }
            set
            {
                username = value;
                OnPropertyChanged();
            }
        }


        public Command Logout { get; }


        public AppShellViewModel()
        {
            Lists = new ObservableCollection<ListData>();

            openList = new Command<ListData>(GoToList);
            newListPage = new Command(GoToNewListPage);
            addList = new Command(async () => await PostList());
            Logout = new Command(OnLogout);

            OnAppearing();
        }

        public async void OnAppearing()
        {
            int id = Preferences.Get("User", 0);
            await GetLists(id);
        }

        private void OnLogout()
        {
            Preferences.Set("User", 0);
            Application.Current.MainPage = new LoginPage();
        }


        // GET ALL USER LISTS FROM DATA BASE
        private async Task GetLists(int UserId)
        {
            client.DefaultRequestHeaders.Accept.Clear();
            client.DefaultRequestHeaders.Accept.Add(new MediaTypeWithQualityHeaderValue("application/json"));

            string url = $"Lists?userId={ UserId }";

            HttpResponseMessage response = await client.GetAsync(url);
            if (response.IsSuccessStatusCode)
            {
                Lists.Clear();
                ObservableCollection<ListData> listsData = await response.Content.ReadAsAsync<ObservableCollection<ListData>>();
                foreach (ListData current in listsData)
                {
                    Lists.Add(current);
                }
            }
            else
            {
                Xamarin.Forms.Page CurrentPage = App.Current.MainPage.Navigation.NavigationStack.LastOrDefault();
                if (CurrentPage != null)
                {
                    await CurrentPage.DisplayAlert("ERROR", "Server Error", "cancel");
                }
            }
        }


        //ADD NEW LIST TO DATA BASE
        private async Task PostList()
        {
            client.DefaultRequestHeaders.Accept.Clear();
            string url = $"Lists";
            int id = Preferences.Get("User", 0);
            ListData list = new ListData()
            {
                id = 0,
                name = Name,
                UserId = id
            };

            await client.PostAsJsonAsync(url, list);

            OnAppearing();

            await Xamarin.Forms.Application.Current.MainPage.Navigation.PopAsync();
        }


        //DELETE LIST FROM DATA BASE
        public async void DeleteList(int id)
        {
            client.DefaultRequestHeaders.Accept.Clear();

            string url = $"ListDelete?id={ id }";

            await client.GetAsync(url);

            int Userid = Preferences.Get("User", 0);
            await GetLists(Userid);
        }





        //NAVIGATION
        private void GoToNewListPage()
        {
            Xamarin.Forms.Application.Current.MainPage.Navigation.PushAsync(new NewListPage());
            Shell.Current.FlyoutIsPresented = false;
        }

        private void GoToList(ListData list)
        {
            Xamarin.Forms.Application.Current.MainPage.Navigation.PushAsync(new TasksPage(list.id));
            Shell.Current.FlyoutIsPresented = false;
        }




    }
}
