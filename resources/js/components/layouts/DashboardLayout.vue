<template>
	<div class="wrapper" :class="{ 'nav-open': $sidebar.showSidebar }">
		<side-bar :background-color="sidebarBackground">
			<template slot-scope="props" slot="links">
				<user-menu></user-menu>
				<sidebar-item
					:link="{
						name: 'Dashboard',
						icon: 'now-ui-icons design_app',
						path: '/',
					}"
				></sidebar-item>
				<sidebar-item
					v-for="(i, key) in objSidebar"
					:key="'side' + key"
					:link="{
						name: i.name,
						icon: 'fab fa-vuejs',
					}"
				>
					<sidebar-item
						v-for="(item, key) in i.sub"
						:key="'sub' + key"
						:link="{ name: item.name, path: item.path }"
					></sidebar-item>
				</sidebar-item>
			</template>
		</side-bar>
		<div class="main-panel">
			<sidebar-share
				:color.sync="sidebarBackground"
				:fixed-navbar.sync="fixedNavbar"
				:sidebarMini.sync="sidebarMini"
				style="cursor: pointer"
			></sidebar-share>
			<router-view name="header"></router-view>

			<div :class="{ content: !$route.meta.hideContent }" @click="toggleSidebar">
				<zoom-center-transition :duration="200" mode="out-in">
					<!-- your content here -->
					<router-view></router-view>
				</zoom-center-transition>
			</div>
			<content-footer v-if="!$route.meta.hideFooter"></content-footer>
		</div>
	</div>
</template>
<script>
import PerfectScrollbar from 'perfect-scrollbar';
import 'perfect-scrollbar/css/perfect-scrollbar.css';

function hasElement(className) {
	return document.getElementsByClassName(className).length > 0;
}

function initScrollbar(className) {
	if (hasElement(className)) {
		new PerfectScrollbar(`.${className}`);
	} else {
		// try to init it later in case this component is loaded async
		setTimeout(() => {
			initScrollbar(className);
		}, 100);
	}
}

import TopNavbar from '../layouts/dashboard/TopNavbar.vue';
import ContentFooter from '../layouts/dashboard/ContentFooter.vue';
import UserMenu from '../layouts/dashboard/Extra/UserMenu.vue';
import { SlideYDownTransition, ZoomCenterTransition } from 'vue2-transitions';
import Vuex from 'vuex';
import SidebarShare from '../layouts/dashboard/Extra/SidebarSharePlugin';

export default {
	components: {
		TopNavbar,
		ContentFooter,
		UserMenu,
		ZoomCenterTransition,
		SidebarShare,
	},
	data() {
		return {
			sidebarBackground: 'black',
			fixedNavbar: false,
			sidebarMini: false,
			objSidebar: [
				{
					icon: 'mdi mdi-crosshairs-gps menu-icon',
					name: 'Quản lý esim',
					route_name: '',
					sub: [
						{
							name: 'Tổng hợp options',
							path: '/product/options',
						},
						{
							name: 'Danh sách title',
							path: '/product',
						},
						{
							name: 'Danh sách phân loại',
							path: '/product/category',
						},
						// {
						//   name: "Danh mục cấp 1",
						//   path: "/product/type",
						// }
					],
				},
				// {
				// 	icon: 'mdi mdi-crosshairs-gps menu-icon',
				// 	name: 'Dịch vụ',
				// 	route_name: '',
				// 	sub: [
				// 		{
				// 			name: 'Danh mục dịch vụ',
				// 			path: '/service/category',
				// 		},
				// 		{
				// 			name: 'Danh sách dịch vụ',
				// 			path: '/service',
				// 		},
				// 	],
				// },
				{
					icon: 'mdi mdi-newspaper menu-icon',
					name: 'Quản lý voucher',
					route_name: '',
					sub: [
						{
							name: 'Danh sách',
							path: '/voucher',
						},
					],
				},
				{
					icon: 'mdi mdi-newspaper menu-icon',
					name: 'Quản lý bảng giá',
					route_name: '',
					sub: [
						{
							name: 'Danh sách',
							path: '/currency-rate',
						},
					],
				},
				{
					icon: 'mdi mdi-newspaper menu-icon',
					name: 'Hoạt động',
					route_name: '',
					sub: [
						{
							name: 'Các hoạt động eSIM',
							path: '/prize',
						},
					],
				},
				{
					icon: 'mdi mdi-newspaper menu-icon',
					name: 'Câu hỏi thường gặp',
					route_name: '',
					sub: [
						{
							name: 'Danh sách',
							path: '/faq',
						},
					],
				},
				{
					icon: 'mdi mdi-newspaper menu-icon',
					name: 'Quản lý từ khóa',
					route_name: '',
					sub: [
						{
							name: 'Danh sách',
							path: '/language/keyword',
						},
					],
				},
				{
					icon: 'mdi mdi-newspaper menu-icon',
					name: 'Trang nội dung',
					route_name: '',
					sub: [
						{
							name: 'Danh sách ',
							path: '/pagecontent',
						},
					],
				},
				{
					icon: 'mdi mdi-newspaper menu-icon',
					name: 'Quản lý bài viết',
					route_name: '',
					sub: [
						{
							name: 'Danh sách bài viết',
							path: '/blogs',
						},
						{
							name: 'Danh mục bài viết',
							path: '/blog/category',
						},
						// {
						//   name: "Loại bài viết",
						//   path: "/blog/type",
						// },
					],
				},
				// {
				// 	icon: 'mdi mdi-newspaper menu-icon',
				// 	name: 'Banner quảng cáo',
				// 	route_name: '',
				// 	sub: [
				// 		{
				// 			name: 'Danh sách',
				// 			path: '/bannerads',
				// 		},
				// 	],
				// },
				{
					icon: 'mdi mdi-file-image menu-icon',
					name: 'Website',
					route_name: '',
					sub: [
						{
							name: 'Quản lý banner',
							path: '/banner',
						},
						{
							name: 'Quản lý đối tác',
							path: '/partner',
						},
						{
							name: 'Cài đặt chung',
							path: '/setting',
						},
					],
				},

				// {
				//   icon: "mdi mdi-shopping-music menu-icon",
				//   name: "Quản lý khuyến mãi",
				//   route_name: "",
				//   sub: [
				//     {
				//       name: "Danh sách",
				//       path: "/promotion",
				//     }
				//   ],
				// },
				{
					icon: 'mdi mdi-shopping-music menu-icon',
					name: 'Quản lý tin nhắn liên hệ',
					route_name: '',
					sub: [
						{
							name: 'Danh sách',
							path: '/messcontact',
						},
					],
				},
				{
					icon: 'mdi mdi-shopping-music menu-icon',
					name: 'Quản lý đơn hàng',
					route_name: '',
					sub: [
						{
							name: 'Đơn hàng cần kiểm tra',
							path: '/bill/draft',
						},
						{
							name: 'Đơn hàng đã xử lý',
							path: '/bill/paymented',
						},
						// {
						// 	name: 'Đơn hàng đã hủy',
						// 	path: '/bill/unpayment',
						// },
					],
				},
				// {
				// 	icon: 'mdi mdi-newspaper menu-icon',
				// 	name: 'Quản lý Review',
				// 	route_name: '',
				// 	sub: [
				// 		{
				// 			name: 'Danh sách dịch vụ',
				// 			path: '/reviewCus',
				// 		},
				// 	],
				// },
			],
		};
	},
	methods: {
		initScrollbar() {
			let isWindows = navigator.platform.startsWith('Win');
			if (isWindows) {
				initScrollbar('sidenav');
			}
		},
		toggleSidebar() {
			if (this.$sidebar.showSidebar) {
				this.$sidebar.displaySidebar(false);
			}
		},
		minimizeSidebar() {
			if (this.$sidebar) {
				this.$sidebar.toggleMinimize();
				let text = this.$sidebar.isMinimized ? 'activated' : 'deactivated';
				this.$notify({ type: 'info', message: `Sidebar mini ${text}...` });
			}
		},
	},
	mounted() {
		let docClasses = document.body.classList;
		let isWindows = navigator.platform.startsWith('Win');
		if (isWindows) {
			// if we are on windows OS we activate the perfectScrollbar function
			initScrollbar('sidebar');
			initScrollbar('sidebar-wrapper');

			docClasses.add('perfect-scrollbar-on');
		} else {
			docClasses.add('perfect-scrollbar-off');
		}
	},
	computed: {},
	watch: {
		sidebarMini() {
			this.minimizeSidebar();
		},
	},
};
</script>
<style lang="scss">
$scaleSize: 0.95;
@keyframes zoomIn95 {
	from {
		opacity: 0;
		transform: scale3d($scaleSize, $scaleSize, $scaleSize);
	}
	to {
		opacity: 1;
	}
}
.main-panel .zoomIn {
	animation-name: zoomIn95;
}
@keyframes zoomOut95 {
	from {
		opacity: 1;
	}
	to {
		opacity: 0;
		transform: scale3d($scaleSize, $scaleSize, $scaleSize);
	}
}
.main-panel .zoomOut {
	animation-name: zoomOut95;
}
</style>
