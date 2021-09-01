import { dashboardBaseUrl } from './baseUrls'

const UsersListView = () => import(/* webpackChunkName: "users-list" */ '../views/users/UsersListView.vue');
const UserView = () => import(/* webpackChunkName: "users-show" */ '../views/users/UserView.vue');
const CreateUserView = () => import(/* webpackChunkName: "users-create" */ '../views/users/CreateUserView.vue');
const EditUserView = () => import(/* webpackChunkName: "users-edit" */ '../views/users/EditUserView.vue');
const IndexView = () => import(/* webpackChunkName: "users-index" */ '../views/users/IndexView.vue');

export default [
	{
		path: `${dashboardBaseUrl}/users`,
		name: 'Users',
		component: IndexView,
		meta: {
			breadcrumb: {
				label: 'Users',
				parent: 'Dashboard'
			}
		},
		children: [
			{
				path: "",
				name: "Users",
				component: UsersListView,
			},
			{
				path: `create`,
				name: 'CreateUser',
				component: CreateUserView,
				meta: {
					requireWaiter: true,
					breadcrumb: {
						label: 'Create user account',
						parent: 'Users'
					}
				}
			},
			{
				path: `:id`,
				name: 'User',
				component: UserView,
				meta: {
					breadcrumb() {
						const { params } = this.$route;

						return {
							label: `User #${params.id}`,
							parent: "Users",
						};
					},
				}
			},
			{
				path: `:id/edit`,
				name: 'EditUser',
				component: EditUserView,
				requireWaiter: true,
				meta: {
					requireWaiter: true,
					breadcrumb: {
						label: 'Edit',
						parent: 'User'
					}
				}
			}
		]
	},

]