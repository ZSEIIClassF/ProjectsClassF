using NotatnikApp.Models;
using NotatnikApp.Views;
using System;
using System.Collections.Generic;
using System.Net.Http;
using System.Net.Http.Headers;
using System.Text;
using System.Threading.Tasks;
using ToDoApp.Views;
using Xamarin.Essentials;
using Xamarin.Forms;

namespace NotatnikApp.ViewModels
{
    internal class RegisterViewModel : BaseViewModel
    {
        public string email { get; set; }
        public string user { get; set; }
        public string password { get; set; }
        public string Confirmpassword { get; set; }

        public Command Register { get; }
        public Command Login { get; }


        public RegisterViewModel()
        {
            Register = new Command(OnRegister);
            Login = new Command(GoToLogin);
        }


        private void GoToLogin()
        {
            Application.Current.MainPage = new LoginPage();
        }


        private async void OnRegister()
        {
            if (password.Length >= 8)
            {
                if (password == Confirmpassword)
                {
                    bool userIsInBase = await UserExist();
                    if (userIsInBase)
                    {
                        throw new Exception("Already exist");
                    }
                    else
                    {
                        await AddUser();
                    }
                }
                else
                {
                    throw new Exception("passwords are not the same");
                    //passwords are not the same
                }
            }
            else
            {
                throw new Exception("password must be 8 lenght");
                //password must be 8 lenght
            }
        }

        private async Task<bool> UserExist()
        {
            client.DefaultRequestHeaders.Accept.Clear();
            client.DefaultRequestHeaders.Accept.Add(new MediaTypeWithQualityHeaderValue("application/json"));

            string url = $"Users?email={ email }";

            HttpResponseMessage response = await client.GetAsync(url);
            if (response.IsSuccessStatusCode)
            {
                UserData userData = await response.Content.ReadAsAsync<UserData>();
                if (userData.pass == null)
                {
                    return false;
                }
                else
                {
                    return true;
                }
            }
            else
            {
                return false;
            }
        }


        private async Task AddUser()
        {
            client.DefaultRequestHeaders.Accept.Clear();
            client.DefaultRequestHeaders.Accept.Add(new MediaTypeWithQualityHeaderValue("application/json"));

            string url = $"Users";
            UserData data = new UserData
            {
                id = 0,
                user = user,
                pass = password,
                email = email
            };

            HttpResponseMessage response = await client.PostAsJsonAsync(url, data);
            if (response.IsSuccessStatusCode)
            {
                GoToLogin();
            }
            else
            {
                throw new Exception("fail");
            }
        }
    }
}
