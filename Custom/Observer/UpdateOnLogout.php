<?php

namespace Tejas\Custom\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Psr\Log\LoggerInterface;

class UpdateOnLogout implements ObserverInterface
{
    protected $customerRepository;
    protected $logger;

    public function __construct(
        CustomerRepositoryInterface $customerRepository,
        LoggerInterface $logger
    ) {
        $this->customerRepository = $customerRepository;
        $this->logger = $logger;
    }

    public function execute(Observer $observer)
    {
        try {
            $customer = $observer->getData('customer');
            $customerId = $customer->getId();
            
            $customerDataObject = $this->customerRepository->getById($customerId);
            
            $customerDataObject->setCustomAttribute('custom_new_attribute', 'logout');
            
            $this->customerRepository->save($customerDataObject);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }
    }
}