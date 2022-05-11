using NotatnikApp.ViewModels;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

using Xamarin.Forms;
using Xamarin.Forms.Xaml;

namespace NotatnikApp.Views
{
    [XamlCompilation(XamlCompilationOptions.Compile)]
    public partial class NewListPage : ContentPage
    {
        public NewListPage()
        {
            InitializeComponent();

            BindingContext = AppShellViewModel.It;
        }
    }
}