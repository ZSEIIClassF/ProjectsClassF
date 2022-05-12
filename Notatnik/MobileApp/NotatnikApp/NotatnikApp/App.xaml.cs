using System;
using ToDoApp.Views;
using Xamarin.Essentials;
using Xamarin.Forms;
using Xamarin.Forms.Xaml;

namespace NotatnikApp
{
    public partial class App : Application
    {

        public App()
        {
            InitializeComponent();

            MainPage = new AppShell();
        }

        protected override void OnStart()
        {
            if (Preferences.Get("User", 0) == 0)
            {
                MainPage = new LoginPage();
            }
            else
            {
                MainPage = new AppShell();
            }
        }

        protected override void OnSleep()
        {
        }

        protected override void OnResume()
        {
        }
    }
}
