using NotatnikApp.Models;
using NotatnikApp.Views;
using System;
using System.Collections.Generic;
using System.Net.Http;
using System.Net.Http.Headers;
using System.Text;
using System.Threading.Tasks;
using Xamarin.Essentials;
using Xamarin.Forms;

namespace NotatnikApp.ViewModels
{
    internal class LoginViewModel : BaseViewModel
    {
        public string email { get; set; }
        public string password { get; set; }

        public Command Login { get; }
        public Command Register { get; }

        public LoginViewModel()
        {
            Login = new Command(async () => await OnLogin());
            Register = new Command(GoToRegister);
        }

        private void GoToRegister()
        {

            Application.Current.MainPage.Navigation.PushModalAsync(new RegisterPage());

        }

        private async Task OnLogin()
        {
            client.DefaultRequestHeaders.Accept.Clear();
            client.DefaultRequestHeaders.Accept.Add(new MediaTypeWithQualityHeaderValue("application/json"));

            string url = $"Users?email={ email }";

            HttpResponseMessage response = await client.GetAsync(url);
            if (response.IsSuccessStatusCode)
            {
                UserData userData = await response.Content.ReadAsAsync<UserData>();
                if (userData.pass == password)
                {
                    Preferences.Set("User", userData.id);
                    Application.Current.MainPage = new AppShell();
                }
                else
                {
                    //wrong pass
                }
            }
            else
            {
                //no user found
            }
        }
    }
}
