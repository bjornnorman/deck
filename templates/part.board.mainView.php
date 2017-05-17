<div id="board-status" ng-if="statusservice.active">
	<div id="emptycontent">
		<div class="icon-{{ statusservice.icon }}"></div>
		<h2>{{ statusservice.title }}</h2>
		<p>{{ statusservice.text }}</p></div>
</div>
<div id="board-header">
	<a href="#" title="<?php p($l->t('All Boards')); ?>">
		<i class="icon icon-home"></i>
	</a>
	<h1 class="title" style="border-bottom: 2px solid #{{boardservice.getCurrent().color }};">
		{{ boardservice.getCurrent().title }}
	</h1>
	<div class="board-action-button">
		<a ui-sref="board.detail({ id: id })" title="<?php p($l->t('Board details')); ?>">
			<i class="icon icon-details"></i>
		</a>
	</div>
	<div class="board-action-button" ng-if="filter!='archive'">
		<a ng-click="switchFilter('archive')" style="opacity:0.5;" title="<?php p($l->t('Show archived cards')); ?>">
			<i class="icon icon-archive"></i>
		</a>
	</div>
	<div class="board-action-button" ng-if="filter=='archive'">
		<a ng-click="switchFilter('')" title="<?php p($l->t('Hide archived cards')); ?>">
			<i class="icon icon-archive"></i>
		</a>
	</div>
	<div id="stack-add" class="board-action-button" ng-if="boardservice.canEdit() && checkCanEdit()">
		<form class="ng-pristine ng-valid" ng-submit="createStack()">
			<input type="text" placeholder="Add a new stack"
				ng-focus="status.addStack=true"
				ng-blur="status.addStack=false"
				ng-model="newStack.title" required
				maxlength="100" />
			<button class="icon icon-add" style="opacity: {{status.addStack ? 1: 0.5}};" type="submit"></button>
		</form>
	</div>
</div>

<div id="board" class="scroll-container" ng-click="sidebar.show=false" ui-sref="board">

	<search on-search="search" class="ng-hide"></search>

	<div id="innerBoard" data-ng-model="stacks">
		<div class="stack" ng-repeat="s in stacks"
			 data-columnindex="{{$index}}" id="column{{$index}}"
			 style="">
			<h2><span ng-show="!s.status.editStack">{{ s.title }}</span>
				<form ng-submit="stackservice.update(s)">
					<input type="text" placeholder="Add a new stack"
						   ng-blur="s.status.editStack=false" ng-model="s.title"
						   ng-if="s.status.editStack" autofocus-on-insert
						   required maxlength="100"/>
				</form>
				<div class="stack-actions">
					<button class="icon icon-confirm" ng-if="s.status.editStack"
							type="submit"></button>
					<button class="icon-rename" ng-if="!s.status.editStack"
							ng-click="s.status.editStack=true"></button>
					<button class="icon-delete"
							ng-click="stackservice.delete(s.id)"></button>
				</div>
			</h2>
			<ul data-as-sortable="sortOptions" is-disabled="!boardservice.canEdit() || filter==='archive'" data-ng-model="s.cards"
				style="min-height: 40px;">
				<li class="card as-sortable-item"
					ng-repeat="c in s.cards"
					data-as-sortable-item
					ng-click="$event.stopPropagation()"
					ui-sref="board.card({boardId: id, cardId: c.id})"
					ng-class="{'archived': c.archived, 'has-labels': c.labels.length>0 }">
					<div data-as-sortable-item-handle>
						<div class="card-upper">
							<i class="icon icon-filetype-text icon-description-marker" ng-if="c.description" title="{{ c.description }}"></i>
							<h3>{{ c.title }}</h3>
							<ul class="labels">
								<li ng-repeat="label in c.labels"
									style="background-color: #{{ label.color }};" title="{{ label.title }}">
									<span>{{ label.title }}</span>
								</li>
							</ul>

						</div>
						<div class="app-popover-menu-utils">
						<button class="card-options icon-more" ng-model="card"></button>
						<div class="popovermenu bubble hidden">
							<ul>
								<li ng-if="filter!=='archive'">
									<a class="menuitem action action-rename permanent"
									   data-action="Archive"
									   ng-click="cardArchive(c); $event.stopPropagation();"><span
											class="icon icon-archive"></span><span><?php p($l->t('Archive')); ?></span></a>
								</li>
								<li ng-if="filter==='archive'">
									<a class="menuitem action action-rename permanent"
									   data-action="Unarchive"
									   ng-click="cardUnarchive(c); $event.stopPropagation();"><span
											class="icon icon-archive"></span><span><?php p($l->t('Unarchive')); ?></span></a>
								</li>
								<li>
									<a class="menuitem action action-delete permanent"
									   data-action="Delete"
									   ng-click="cardDelete(c)"><span
											class="icon icon-delete"></span><span><?php p($l->t('Delete')); ?></span></a>
								</li>
							</ul>
						</div>
							</div>
						<div class="card-assignees" ng-if="c.assignees">
							<!--   <div class="avatar" avatar user="{{c.owner}}" size="24"></div>//-->
						</div>
						<!--<span class="info due"><i class="fa fa-clock-o" aria-hidden="true"></i> <span>Today</span></span>
						<span class="info tasks"><i class="fa fa-list" aria-hidden="true"></i> <span>3/12</span></span>
						//-->


					</div>
				</li>
			</ul>
			<!-- CREATE CARD //-->
			<div class="card create"
				 style="background-color:#{{ boardservice.getCurrent().color }};" ng-if="boardservice.canEdit() && checkCanEdit() && filter!=='archive'">
				<form ng-submit="createCard(s.id, newCard.title)">
					<h3 ng-if="status.addCard[s.id]">
						<input type="text" autofocus-on-insert
							   ng-model="newCard.title"
							   ng-blur="status.addCard[s.id]=false"
							   style="color:{{ boardservice.getCurrent().color | textColorFilter }}; border-color:{{ boardservice.getCurrent().color | textColorFilter }};"
							   maxlength="100"
							   required placeholder="<?php p($l->t('Enter a card title')); ?>"/>
					</h3>
				</form>
				<div ng-if="!status.addCard[s.id]" ng-click="status.addCard[s.id]=true">
					<i class="icon icon-add{{ boardservice.getCurrent().color | iconWhiteFilter }}"></i>
				</div>
			</div>
		</div>

	</div>

</div>
