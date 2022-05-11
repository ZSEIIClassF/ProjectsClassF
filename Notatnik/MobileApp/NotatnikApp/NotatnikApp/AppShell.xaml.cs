﻿using NotatnikApp.Models;
using NotatnikApp.ViewModels;
using System;
using System.Collections.Generic;
using Xamarin.Forms;

namespace NotatnikApp
{
    public partial class AppShell : Xamarin.Forms.Shell
    {

        AppShellViewModel viewModel;

        public AppShell()
        {
            InitializeComponent();

            BindingContext = viewModel = AppShellViewModel.It;
        }



        private void Button_Clicked(object sender, EventArgs e)
        {

                var cb = (Button)sender;
                var item = (ListData)cb.BindingContext;
                var id = item.id;

                viewModel.DeleteList(id);

        }

    }
}
