using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Net.Http;
using System.Runtime.CompilerServices;
using System.Text;
using System.Threading.Tasks;

namespace NotatnikApp.ViewModels
{
    internal class BaseViewModel : INotifyPropertyChanged
    {
        public HttpClient client = new HttpClient();

        public BaseViewModel()
        {
            client.BaseAddress = new Uri("http://notatnikapi.projectsclassf.pl/");
        }




        //INotifyPropertyChanged Function to update UI when backend runs
        public event PropertyChangedEventHandler PropertyChanged;

        public void OnPropertyChanged([CallerMemberName] string name = null)
        {
            PropertyChanged?.Invoke(this, new PropertyChangedEventArgs(name));
        }
    }
}
