{!! Menu::main()
        ->withoutWrapperTag()
        ->withoutParentTag()
        ->addItemClass('text-base font-medium text-slate-500 hover:[&:not(.active)]:text-slate-900')
        ->setActiveClass('active text-brand font-semibold')
        ->setActiveClassOnLink()
!!}
