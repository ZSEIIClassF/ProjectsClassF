using NotatnikApp.Models;
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
    public partial class TasksPage : ContentPage
    {
        TasksViewModel viewModel;
        public TasksPage(int id)
        {
            InitializeComponent();
            BindingContext = viewModel = TasksViewModel.It;
            viewModel.ListId = id;
        }

        void OnCheckBoxCheckedChanged(object sender, CheckedChangedEventArgs e)
        {
            try
            {
                var cb = (CheckBox)sender;
                var item = (TaskData)cb.BindingContext;
                var id = item.id;

                viewModel.DoneChanged(id, cb.IsChecked);
            }catch{}
        }

        private void Button_Clicked(object sender, EventArgs e)
        {
            try
            {
                var cb = (ImageButton)sender;
            var item = (TaskData)cb.BindingContext;
            var id = item.id;

            viewModel.DeleteTask(id);
            }
            catch { }
        }
    }
}